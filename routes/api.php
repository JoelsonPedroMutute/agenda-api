<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ReminderController;
use App\Http\Controllers\Api\V1\UserController;

use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\V1\Admin\ReminderController as AdminReminderController;

//  ROTAS DE AUTENTICAÇÃO
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

//  ROTA PÚBLICA PARA OBTER USUÁRIO AUTENTICADO
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//  ROTAS PARA USUÁRIO COMUM
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Perfil do usuário
    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);
    Route::put('user/password', [UserController::class, 'changePassword']);

    // Agendamentos e lembretes
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('reminders', ReminderController::class);
});

//  ROTAS EXCLUSIVAS PARA ADMIN
Route::middleware(['auth:sanctum', 'admin'])->prefix('v1/admin')->group(function () {
    Route::apiResource('users', AdminUserController::class);
    Route::apiResource('appointments', AdminAppointmentController::class);
    Route::apiResource('reminders', AdminReminderController::class);
});