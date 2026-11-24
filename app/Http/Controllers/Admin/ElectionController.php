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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
