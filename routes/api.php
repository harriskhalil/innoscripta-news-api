<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferencesController;

Route::middleware('auth:sanctum')->group(function (){


    Route::post('auth/logout', [AuthController::class, 'logout']);


    Route::controller(ArticleController::class)->group(function () {

        Route::get('/articles', 'index');
        Route::get('/article/{article}', 'show');
        Route::get('/articles/search', 'search');

    });


    Route::controller(UserPreferencesController::class)->group(function () {

        Route::post('/preferences', 'setPreferences');
        Route::get('/preferences','getPreferences');
        Route::get('/personalized-feed', 'fetchPersonalizedFeed');

    });


});





Route::controller(AuthController::class)->prefix('auth')->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/reset', 'send_reset_password_token');
    Route::put('/password/update', 'update_user_password');

});
