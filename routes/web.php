<?php

use App\Http\Controllers\Elections;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin/elections');
});

Route::post('/create_election', [Elections::class, 'createElection']);

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('elections', ElectionController::class)
        ->only(['index', 'store', 'edit', 'update']);
    
    Route::resource('positions', PositionController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    
    Route::post('positions/{position}/move/{direction}', [PositionController::class, 'move'])
        ->whereIn('direction', ['up', 'down'])
        ->name('positions.move');
});