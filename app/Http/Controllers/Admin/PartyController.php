<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Models\Party;
use App\Models\Election;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PartyController extends Controller
{
    public function index(): View
    {
        $parties = Party::with('election')->orderBy('name')->get();
        $elections = Election::orderBy('name')->get();

        return view('admin.parties.index', compact('parties', 'elections'));

    }

    public function store(StorePartyRequest $request): RedirectResponse
    {
        Party::create($request->validated());

        return redirect()->route('admin.parties.index');
    }

    public function update(UpdatePartyRequest $request, Party $party): RedirectResponse
    {
        $party->update($request->validated());

        return redirect()->route('admin.parties.index');
    }

    public function destroy(Party $party): RedirectResponse
    {
        if ($party->candidates()->exists()) {
            return redirect()->route('admin.parties.index')
                ->with('error', 'Cannot delete a party that still has candidates.');
        }

        $party->delete();

        return redirect()->route('admin.parties.index')
            ->with('status', 'Party deleted.');
    }
}
