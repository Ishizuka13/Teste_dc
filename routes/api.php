<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;

// Rotas Públicas
Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/date', [ReservationController::class, 'get_reservations_by_date']);

Route::post('/register/user', [RegisterController::class, 'register']);


// Rotas de Usuário com Middleware
// Route::middleware(['auth:api'])->group(function () {
Route::prefix('/user')->group(function () {
    Route::get('/reservations/{user_id}', [ReservationController::class, 'get_reservations_by_users']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    Route::post('/logout', [LoginController::class, 'logout']);

    // Rotas de Quartos e Reservas para Usuários
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'create']);
});
// });

// Rotas de Admin  com Middleware
Route::prefix('admin')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

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











































// // Route::middleware(['auth'])->group(function () {
// Route::get('/rooms', [RoomController::class, 'index']);
// Route::get('/reservations', [ReservationController::class, 'index']);
// Route::get('/reservations/date', [ReservationController::class, 'get_reservations_by_date']);
// Route::post('/reservations', [ReservationController::class, 'create']);
// Route::get('/email', [AuthMailController::class, 'sendReserveMail']);
// // });


// Route::group(['prefix' => 'auth'], function () {
//     //     // Route::post('login', [LoginController::class, '']);

//     Route::prefix('/user')->group(function () {
//         Route::get('/reservations/{userid}', [ReservationController::class, 'get_reservations_by_users']);
//         Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
//         //         Route::post('/login', [LoginController::class, 'login']);
//         //         Route::post('/register', [RegisterController::class, 'register']);
//         //         Route::post('/logout', [LoginController::class, 'logout']);
//     });

//     Route::prefix('admin')->group(function () {
//         Route::post('/login', [LoginController::class, 'login_admin']);
//         Route::post('/logout', [LoginController::class, 'logout']);

//         //         // Route::middleware(['auth'])->group(function () {

//         //users
//         Route::get('/users', [LoginController::class, 'get_users']);


//         //rooms
//         Route::post('/rooms', [RoomController::class, 'create']);
//         Route::put('/rooms/{id}', [RoomController::class, 'update']);
//         // Route::delete('/rooms/{id}', [RoomController::class, 'delete']);

//         //reservations
//         Route::put('/reservations/{id}', [ReservationController::class, 'update']);
//         Route::delete('/reservations/{id}', [ReservationController::class, 'admin_destroy']);


//         //relatory
//         Route::get('/relatory', [HotelController::class, 'relatory']);

//         //         // });
//     });
// });
