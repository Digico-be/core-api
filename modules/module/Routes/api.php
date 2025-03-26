<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api',
], function () {
    Route::middleware(['auth:api', "auth.tenant"])->group(function(){
        Route::get('/modules/{module}/users', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'users']);
        Route::post('/modules/{module}/attach', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'attach']);
        Route::post('/modules/{module}/detach', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'detach']);
    });

    Route::middleware(['auth:api'])->group(function(){
        Route::resource("/modules", \Diji\Module\Http\Controllers\ModuleController::class)->only(['index']);
    });
});



