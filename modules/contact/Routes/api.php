<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api',
], function () {
    Route::middleware(['auth:api', "auth.tenant"])->group(function () {
        Route::resource("/contacts", \Diji\Contact\Http\Controllers\ContactController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });
});
