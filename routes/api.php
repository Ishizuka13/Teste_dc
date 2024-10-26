<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;

// Rotas Públicas
Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/date', [ReservationController::class, 'get_reservations_by_date']);
Route::post('admin/login', [LoginController::class, 'login_admin']);
Route::post('user/login', [LoginController::class, 'login_user']);

Route::post('/register/user', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout']);


// Rotas de Usuário com Middleware
Route::middleware(['auth'])->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/reservations/{user_id}', [ReservationController::class, 'get_reservations_by_users']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);


        // Rotas de Quartos e Reservas para Usuários
        Route::get('/rooms', [RoomController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'create']);
    });
});

// Rotas de Admin  com Middleware

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {

        // Usuários
        Route::get('/users', [LoginController::class, 'get_users']);

        // Quartos
        // Route::post('/rooms', [RoomController::class, 'create']);
        Route::put('/rooms/{id}', [RoomController::class, 'update']);

        // Reservas
        Route::put('/reservations/{id}', [ReservationController::class, 'update']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'admin_destroy']);

        // Relatórios
        Route::get('/reports', [HotelController::class, 'reports']);
    });
});
