<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api',
], function () {
    Route::middleware(['auth:api', "auth.tenant"])->group(function(){

        // Routes resource pour les assistants
        Route::resource('/assistants', \Diji\Ia\Http\Controllers\AssistantController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        // Routes pour attacher et détacher des assistants pour un tenant spécifique via le tenant central
        Route::post('/assistants/{assistantId}/attach', [\Diji\Ia\Http\Controllers\AssistantController::class, 'attachAssistant']);
        Route::post('/assistants/{assistantId}/detach', [\Diji\Ia\Http\Controllers\AssistantController::class, 'detachAssistant']);

        // Optionnel : Pour obtenir tous les assistants associés au tenant
        Route::get('/assistants', [\Diji\Ia\Http\Controllers\AssistantController::class, 'index']);
    });
});


