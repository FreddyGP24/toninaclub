<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PublicHotelController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicHotelController::class, 'index'])->name('home');
Route::get('/mapa-hoteles', [PublicHotelController::class, 'map'])->name('hotels.map');
Route::get('/hoteles/{hotel}', [PublicHotelController::class, 'show'])->name('hotels.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registro', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/mis-reservaciones', [ReservationController::class, 'index'])
        ->name('reservations.index');
    Route::get('/hoteles/{hotel}/habitaciones/{room}/reservar', [ReservationController::class, 'create'])
        ->name('reservations.create');
    Route::post('/hoteles/{hotel}/habitaciones/{room}/reservar', [ReservationController::class, 'store'])
        ->name('reservations.store');
    Route::patch('/mis-reservaciones/{reservation}/cancelar', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
});

Route::prefix('panel')->name('panel.')
    ->middleware(['auth', 'role:propietario,administrador'])
    ->group(function () {
        Route::resource('hoteles', HotelController::class)
            ->except('show')
            ->parameters(['hoteles' => 'hotel'])
            ->names('hotels');

        Route::get('/hoteles/{hotel}/habitaciones', [RoomController::class, 'index'])
            ->name('rooms.index');
        Route::get('/hoteles/{hotel}/habitaciones/crear', [RoomController::class, 'create'])
            ->name('rooms.create');
        Route::post('/hoteles/{hotel}/habitaciones', [RoomController::class, 'store'])
            ->name('rooms.store');
        Route::get('/hoteles/{hotel}/habitaciones/{room}/editar', [RoomController::class, 'edit'])
            ->name('rooms.edit');
        Route::put('/hoteles/{hotel}/habitaciones/{room}', [RoomController::class, 'update'])
            ->name('rooms.update');
        Route::delete('/hoteles/{hotel}/habitaciones/{room}', [RoomController::class, 'destroy'])
            ->name('rooms.destroy');

        Route::get('/reservaciones', [ReservationController::class, 'ownerIndex'])
            ->name('reservations.index');
        Route::patch('/reservaciones/{reservation}/estado', [ReservationController::class, 'updateStatus'])
            ->name('reservations.status');
    });
