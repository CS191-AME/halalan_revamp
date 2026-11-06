<?php

use App\Http\Controllers\Elections;
use App\Http\Controllers\Admin\ElectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin/elections');
});

Route::post('/create_election', [Elections::class, 'createElection']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('elections', ElectionController::class)
        ->only(['index', 'store']);
});