<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StorePositionRequest;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PositionController extends Controller
{
    public function index(Request $request)
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

    public function store(StorePositionRequest $request)
    {
        $data = $request->validated();

        $electionId = $data['election_id'];
        unset($data['election_id']);


        DB::transaction(function () use ($data, $electionId) {

            $election = Election::lockForUpdate()->findOrFail($electionId);
            
            if (!$election->isDraft()) 
            {
                throw ValidationException::withMessages(['state' => 'Positions cannot be added once the election has started.',]);
            }

            $election->positions()->create($data);
        });

        return redirect()->route('admin.positions.index', ['election_id' => $electionId,])->with('status', 'Position added.');
    }
}
