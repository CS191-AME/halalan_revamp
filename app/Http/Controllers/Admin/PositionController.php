<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePositionRequest;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'election_id' => ['nullable', 'integer', 'exists:elections,id'],
        ]);

        $elections = Election::orderBy('name')->get();

        $selectedElection = null;
        $positions = collect();

        if ($request->filled('election_id')) {
            $selectedElection = Election::findOrFail(
                $request->input('election_id')
            );

            $positions = $selectedElection->positions;
        }

        return view('admin.positions.index', compact(
            'elections',
            'selectedElection',
            'positions'
        ));
    }

    public function store(StorePositionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $electionId = $data['election_id'];
        unset($data['election_id']);


        DB::transaction(function () use ($data, $electionId) {

            $election = Election::lockForUpdate()->findOrFail($electionId);

            if (!$election->isDraft()) {
                throw ValidationException::withMessages(['state' => 'Positions cannot be added once the election has started.',]);
            }

            $election->positions()->create($data);
        });

        return redirect()->route('admin.positions.index', ['election_id' => $electionId,])->with('status', 'Position added.');
    }

    public function update(UpdatePositionRequest $request, Position $position): RedirectResponse
    {
        DB::transaction(function () use ($request, $position) {
            $election = Election::lockForUpdate()->findOrFail($position->election_id);

            if (!($election->isDraft())) {
                throw ValidationException::withMessages(['state' => 'Positions cannot be edited once the election has started']);
            }

            $position->update($request->validated());
        });

        return redirect()->route('admin.positions.index', ['election_id' => $position->election_id])->with('status', 'Position updated.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        $backTo = redirect()->route('admin.positions.index', ['election_id' => $position->election_id]);

        $error = DB::transaction(function () use ($position) {
            $election = Election::lockForUpdate()->findOrFail($position->election_id);

            if (!$election->isDraft()) {
                return 'Positions cannot be deleted once the election has started.';
            }

            if ($position->candidates()->exists()) {
                return 'Cannot delete a position that still has candidates.';
            }

            if (DB::table('blocks_elections_positions')->where('position_id', $position->id)->exists()) {
                return 'Cannot delete a position that a block can vote on.';
            }

            $position->delete();

            return null;
        });

        if ($error !== null) {
            return $backTo->with('error', $error);
        }

        return $backTo->with('status', 'Position deleted.');
    }
}
