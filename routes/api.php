<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\DashboardControllerApi;
use App\Http\Controllers\Api\CuacaController;

// =============================================
// PUBLIC ROUTES
// =============================================
Route::post('/login', [AuthControllerApi::class, 'login']);
Route::post('/register', [AuthControllerApi::class, 'register']);

// =============================================
// PROTECTED ROUTES (perlu token)
// =============================================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/dashboard', [DashboardControllerApi::class, 'index']);
    Route::get('/cuaca/terkini', [CuacaController::class, 'terkini']);
});