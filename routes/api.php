<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\UserController;

// Rotas para o Login, Logout e Cadastro de um usuário
Route::prefix('auth')->group(function(){

Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para vizualizar, atualizar alguns dados do usuário ?
Route::middleware('auth:sanctum')->prefix('v1')->group(function ()
{
Route::get('user', [UserController::class,  'show']);
Route::put('user', [UserController::class,  'update']);
Route::put('user/password', [UserController::class, 'changePassword']);
});

// Rotas para os reminders e appointments com todos os verbos http
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('appointments', AppointmentController::class);
    
});
