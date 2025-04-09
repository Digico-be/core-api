<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);


Route::middleware(['auth:api','auth.tenant'])->prefix('auth')->group(function () {
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'getAuthenticatedUser']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware(['auth:api','auth.tenant'])->group(function () {
    Route::resource('/metas', \App\Http\Controllers\MetaController::class)->only(["show","update"]);
    Route::resource('/uploads', \App\Http\Controllers\UploadController::class)->only('store');
    Route::post('/send-email', [\App\Http\Controllers\MailController::class, 'send']);

});

Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [App\Http\Controllers\ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/reset-password', [App\Http\Controllers\ResetPasswordController::class, 'showResetForm'])->name('password.reset');


