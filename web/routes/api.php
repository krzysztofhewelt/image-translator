<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware(['auth:api'])->group(function () {
    Route::controller(ImageController::class)->prefix('images')->group(function () {
        Route::post('translate', 'translate');
        Route::post('translate', 'add');
        Route::get('all', 'all');
        Route::get('{id}/edit', 'edit');
        Route::patch('{id}/edit', 'edit');
        Route::delete('{id}/delete', 'delete');
        Route::delete('{id}/publish', 'publish');
    });
});