<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Http\Requests\StoreElectionRequest;
use App\Http\Requests\UpdateElectionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $elections = Election::with('parent')->orderBy('id')->get();

        $parents = Election::orderBy('name')->get();

        return view('admin.elections.index', compact('elections', 'parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreElectionRequest $request): RedirectResponse
    {
        Election::create($request->validated());

        return redirect()->route('admin.elections.index')->with('status', 'Election created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElectionRequest $request, Election $election): RedirectResponse
    {
        $election->update($request->validated());

        return redirect()->route('admin.elections.index')->with('status', 'Election updated.');
    }

    /**
     * Start an election and its direct children.
     */
    public function start(Election $election): RedirectResponse
    {
        if ($election->is_running) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'This election is already running.');
        }

        // Direct children, each with a count of its positions
        $children = $election->children()->withCount('positions')->get();
        $hasChildren = $children->isNotEmpty();
        $ownPositionCount = $election->positions()->count();

        // An election with no children must have at least one position
        if (!$hasChildren && $ownPositionCount === 0) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'Add at least one position before starting this election.');
        }

        // Every child must have at least one position
        $emptyChildren = $children->where('positions_count', 0);

        if ($emptyChildren->isNotEmpty()) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'Cannot start. These child elections have no positions: '
                    . $emptyChildren->pluck('name')->join(', ') . '.');
        }

        // The election itself plus its direct children
        $ids = $children->pluck('id')->prepend($election->id)->all();

        DB::transaction(function () use ($ids) {
            // started_at means "first ever started", so only fill it if empty
            Election::whereIn('id', $ids)
                ->whereNull('started_at')
                ->update(['started_at' => now()]);

            Election::whereIn('id', $ids)
                ->update(['is_running' => true, 'results_available' => false]);
        });

        $redirect = redirect()->route('admin.elections.index')
            ->with('status', 'Election started.');

        // TODO: candidates are not checked yet before starting
        if ($hasChildren && $ownPositionCount === 0) {
            $redirect->with('warning', 'This election has no positions of its own. Only its child elections have positions.');
        }

        return $redirect;
    }

    /**
     * Stop an election and all of its descendants.
     */
    public function stop(Election $election): RedirectResponse
    {
        if (!$election->is_running) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'This election is not running.');
        }

        // The election, its children, grandchildren, and so on
        $ids = Election::subtreeIds($election->id);

        Election::whereIn('id', $ids)->update(['is_running' => false]);

        return redirect()->route('admin.elections.index')
            ->with('status', 'Election stopped.');
    }

    /**
     * Show results for a stopped election and its descendants.
     */
    public function showResults(Election $election): RedirectResponse
    {
        // Results only after the election was started and then stopped
        if ($election->isDraft() || $election->is_running) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'Results can only be shown after the election has been started and stopped.');
        }

        $ids = Election::subtreeIds($election->id);

        // Skip descendants that are still drafts or running
        Election::whereIn('id', $ids)
            ->whereNotNull('started_at')
            ->where('is_running', false)
            ->update(['results_available' => true]);

        return redirect()->route('admin.elections.index')
            ->with('status', 'Results are now visible.');
    }

    /**
     * Hide results for an election and its descendants.
     */
    public function hideResults(Election $election): RedirectResponse
    {
        $ids = Election::subtreeIds($election->id);

        Election::whereIn('id', $ids)->update(['results_available' => false]);

        return redirect()->route('admin.elections.index')
            ->with('status', 'Results are now hidden.');
    }

    /**
     * Delete a draft election.
     */
    public function destroy(Election $election): RedirectResponse
    {
        $backTo = redirect()->route('admin.elections.index');

        $error = DB::transaction(function () use ($election) {
            // Lock the row so nobody can start it between the checks and the delete
            $locked = Election::lockForUpdate()->findOrFail($election->id);

            if (!$locked->isDraft()) {
                return 'Only draft elections can be deleted.';
            }

            // TEMPORARY: block instead of deleting related rows along with the election.
            // Revisit once we decide whether to cascade (positions, parties, blocks).
            if ($locked->children()->exists()) {
                return 'Cannot delete an election that has child elections.';
            }

            if ($locked->positions()->exists()) {
                return 'Cannot delete an election that still has positions.';
            }

            if ($locked->parties()->exists()) {
                return 'Cannot delete an election that still has parties.';
            }

            if (DB::table('blocks_elections')->where('election_id', $locked->id)->exists()) {
                return 'Cannot delete an election that is linked to a block.';
            }

            $locked->delete();

            return null;
        });

        if ($error !== null) {
            return $backTo->with('error', $error);
        }

        return $backTo->with('status', 'Election deleted.');
    }
}
