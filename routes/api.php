<?php

use App\Http\Controllers\Api\AI\AiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Todo\TodoController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->prefix('/todo')->group(function () {
    Route::get('/resume', [AiController::class, 'getResume']);

    Route::controller(TodoController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});
