<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api',
], function () {
    Route::middleware(['auth:api', "auth.tenant"])->group(function(){

        // Routes resource pour les assistants
        Route::resource('/assistants', \Diji\Ia\Http\Controllers\AssistantController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        // Optionnel : Pour obtenir tous les assistants associés au tenant
        Route::get('/assistants', [\Diji\Ia\Http\Controllers\AssistantController::class, 'index']);


        Route::resource('/threads', \Diji\Ia\Http\Controllers\ThreadController::class)->only([
            'store', 'show', 'destroy'
        ]);
    });
});


