<?php

use App\Http\Controllers\Elections;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\PartyController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::post('/create_election', [Elections::class, 'createElection']);

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('elections', ElectionController::class)
        ->only(['index', 'store', 'update']);

    Route::post('elections/{election}/start', [ElectionController::class, 'start'])
        ->name('elections.start');

    Route::post('elections/{election}/stop', [ElectionController::class, 'stop'])
        ->name('elections.stop');

    Route::post('elections/{election}/results/show', [ElectionController::class, 'showResults'])
        ->name('elections.results.show');

    Route::post('elections/{election}/results/hide', [ElectionController::class, 'hideResults'])
        ->name('elections.results.hide');

    Route::resource('positions', PositionController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::post('positions/{position}/move/{direction}', [PositionController::class, 'move'])
        ->whereIn('direction', ['up', 'down'])
        ->name('positions.move');

    Route::resource('parties', PartyController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
