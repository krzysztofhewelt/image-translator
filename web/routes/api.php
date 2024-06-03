<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeSelfPasswordController;
use App\Http\Controllers\TranslateController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
  Route::post('login', 'login');
  Route::post('register', 'register');
  Route::post('logout', 'logout');
  Route::post('refresh', 'refresh');
});

Route::middleware(['auth:api'])->group(function () {
  Route::controller(TranslateController::class)
    ->prefix('translates')
    ->group(function () {
      Route::post('translate', 'translateImage');
      Route::post('{id}/ocr-retranslate', 'runOCRAndReTranslate');
      Route::post('text-translate', 'runTranslateText');

      Route::get('search', 'searchTranslationsByTitle');
      Route::get('{id}/show', 'show');
      Route::post('{id}/update', 'update');
      Route::post('{id}/publish', 'publishTranslation');
      Route::delete('{id}/delete', 'delete');
    });

  Route::post('change-password', [ChangeSelfPasswordController::class, 'changePassword']);
});
