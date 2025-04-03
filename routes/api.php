<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);


Route::middleware(['auth:api','auth.tenant'])->prefix('auth')->group(function () {
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware(['auth:api','auth.tenant'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);

    Route::resource('/metas', \App\Http\Controllers\MetaController::class)->only(["show","update"]);
    Route::resource('/uploads', \App\Http\Controllers\UploadController::class)->only('store');
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);

});
