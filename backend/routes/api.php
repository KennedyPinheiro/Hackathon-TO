<?php

use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/senha', [AuthController::class, 'alterarSenha',]);
    });
});


Route::middleware('auth:sanctum')->prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index',])->middleware('permission:usuarios.visualizar');
    Route::post('/', [UserController::class, 'store',])->middleware('permission:usuarios.criar');
    Route::put('/{user}/senha', [UserController::class, 'alterarSenha',])->middleware('role:admin');
    Route::get('/{user}', [UserController::class, 'show',])->middleware('permission:usuarios.visualizar');
    Route::put('/{user}', [UserController::class, 'update',])->middleware('permission:usuarios.editar');
    Route::delete('/{user}', [UserController::class, 'destroy',])->middleware('permission:usuarios.excluir')->middleware('prevent.self.deletion');
});

Route::middleware('auth:sanctum')->prefix('auditoria')->group(function () {
    Route::get('/', [AuditController::class, 'index'])->middleware('permission:auditoria.visualizar');
    Route::get('/{audit}', [AuditController::class, 'show'])->middleware('permission:auditoria.visualizar');
});
