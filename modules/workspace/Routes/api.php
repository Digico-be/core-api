<?php

use Diji\Workspace\Http\Controllers\UserTenantController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'api',
], function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/user/tenants', [UserTenantController::class, 'index']);

    });
});
