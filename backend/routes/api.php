<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/me', function (Illuminate\Http\Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->user()->load('roles', 'permissions'),
            ]);
        });
    });
});
