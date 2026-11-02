<?php

use App\Http\Controllers\Elections;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('elections');
});

Route::post('/create_election', [Elections::class, 'createElection']);