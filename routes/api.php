<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/csrftoken',[UserController::class, 'csrftoken']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('posts', PostController::class);
    });
});