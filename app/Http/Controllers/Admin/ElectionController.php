<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Http\Requests\StoreElectionRequest;
use App\Http\Requests\UpdateElectionRequest;

use Illuminate\Http\Request;

class ElectionController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function store(StoreElectionRequest $request)
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
     * Show the form for editing the specified resource.
     */
    public function edit(Election $election)
    {
        // Editable parent elections
        // IDEA: Make it so that the election cannot be placed under itself or one of its own child elections
        // $parents = Election::where('id', '!=', $election->id)
        //     ->orderBy('name')
        //     ->get();

        // return view('admin.elections.edit', compact('election', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElectionRequest $request, Election $election)
    {
        $election->update($request->validated());

        return redirect()->route('admin.elections.index')->with('status', 'Election updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
