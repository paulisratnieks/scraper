<?php

declare(strict_types=1);

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthenticationController;

Route::middleware('guest')->group(function (): void {
    Route::post('login', [AuthenticationController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::resource('articles', ArticleController::class)->only('index', 'destroy');
    Route::post('logout', [AuthenticationController::class, 'logout']);
});
