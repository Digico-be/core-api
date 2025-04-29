<?php

use Illuminate\Support\Facades\Route;
use Diji\Ia\Http\Controllers\AssistantController;
use Diji\Ia\Http\Controllers\ThreadController;
use Diji\Ia\Http\Controllers\FileController;
use Diji\Ia\Http\Controllers\FileMessageController;

Route::group(
    [
        'prefix'     => 'api',
        'middleware' => ['auth:api', 'auth.tenant'],
    ],
    function () {
        /*
        |--------------------------------------------------------------------------
        | Assistants
        |--------------------------------------------------------------------------
        | - index, store, show, update, destroy
        */
        Route::resource('assistants', AssistantController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Threads
        |--------------------------------------------------------------------------
        | - /threads/find  (recherche par assistant + module)
        | - store, show, destroy
        */
        Route::get('threads/find', [ThreadController::class, 'find']);
        Route::resource('threads', ThreadController::class)
            ->only(['store', 'show', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | Fichiers OpenAI  (métadonnées uniquement)
        |--------------------------------------------------------------------------
        */
        Route::apiResource('files', FileController::class);

        /*
        |--------------------------------------------------------------------------
        | Pivot Fichier ↔ Message
        |--------------------------------------------------------------------------
        | - index  : lister les liens (option ?thread_openai_id=…)
        | - store  : enregistrer file_openai_id + message_openai_id (+ thread)
        */
        Route::apiResource('file-messages', FileMessageController::class)
            ->only(['index', 'store']);
    }
);
