<?php

use App\Http\Controllers\Elections;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin/elections');
});

Route::post('/create_election', [Elections::class, 'createElection']);