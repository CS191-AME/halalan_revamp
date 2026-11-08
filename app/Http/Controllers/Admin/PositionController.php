<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Election;

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
}
