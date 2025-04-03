<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api',
], function () {
    Route::middleware(['auth:api', "auth.tenant"])->group(function(){
        Route::resource("/modules", \Diji\Module\Http\Controllers\ModuleController::class)->only(['index']);

        Route::get('/modules/user/{userId}', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'modulesForUser']);
        Route::post('/modules/{moduleId}/attach', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'attach']);
        Route::post('/modules/{module}/detach', [\Diji\Module\Http\Controllers\ModuleUserController::class, 'detach']);
    });
});



