<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ReminderController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Auth\PasswordResetController;

// ROTAS DE AUTENTICAÇÃO
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

// ROTA PÚBLICA PARA OBTER USUÁRIO AUTENTICADO (deprecável caso já tenha a rota /v1/user)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ROTAS AUTENTICADAS (usuário comum e admin)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Perfil do usuário autenticado (user ou admin)
    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);
    Route::put('user/password', [UserController::class, 'changePassword']);
    Route::delete('user', [UserController::class, 'destroySelf']);




    // Usuários (somente admin)
    Route::get('admin/users', [UserController::class, 'index']);
    Route::post('admin/users', [UserController::class, 'store']);
    Route::get('admin/users/{id}', [UserController::class, 'showById']);
    Route::put('admin/users/{id}', [UserController::class, 'updateById']);
    Route::delete('admin/users/{id}', [UserController::class, 'destroyById']);
    Route::patch('admin/users/{id}/restore', [UserController::class, 'restoreById']);
 

    // Agendamentos e lembretes com controladores unificados
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('reminders', ReminderController::class);

    //  Versões simplificadas (sem relacionamentos) de agendamentos e lembretes
    Route::get('appointments-simple', [AppointmentController::class, 'indexSimple']);
    Route::get('reminders-simple', [ReminderController::class, 'indexSimple']);
});

// ROTAS DE RECUPERAÇÃO DE SENHA (públicas)
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'message' => 'Página de redefinição de senha (frontend)',
        'token' => $token,
    ]);
})->name('password.reset');
