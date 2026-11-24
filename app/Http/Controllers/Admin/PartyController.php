<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;

class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::with('election')->orderBy('name')->get();

        return view('admin.parties.index', compact('parties'));

    }
}
