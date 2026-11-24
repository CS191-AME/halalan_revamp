<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartyRequest;
use App\Models\Party;
use App\Models\Election;

class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::with('election')->orderBy('name')->get();
        $elections = Election::orderBy('name')->get();

        return view('admin.parties.index', compact('parties', 'elections'));

    }

    public function store(StorePartyRequest $request)
    {
        Party::create($request->validated());

        return redirect()->route('admin.parties.index');
    }
}
