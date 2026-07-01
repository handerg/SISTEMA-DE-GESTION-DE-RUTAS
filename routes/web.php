<?php

use App\Http\Controllers\AuthController;
use app\Http\Controllers\ControllerConductores;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/autos', [AuthController::class, 'autos'])->name('autos');
    Route::post('/autos', [AuthController::class, 'storeAuto'])->name('autos.store');
    Route::get('/conductores', [AuthController::class, 'conductores'])->name('conductores');
    Route::post('/conductores', [AuthController::class, 'storeConductor'])->name('conductores.store');
    Route::put('/conductores/{conductor}', [AuthController::class, 'updateConductor'])->name('conductores.update');
    Route::delete('/conductores/{conductor}', [AuthController::class, 'destroyConductor'])->name('conductores.destroy');
    Route::get('/usuarios', [AuthController::class, 'usuarios'])->name('usuarios');
    Route::post('/usuarios', [AuthController::class, 'storeUsuario'])->name('usuarios.store');
    Route::post('/usuarios/{user}/toggle', [AuthController::class, 'toggleUsuario'])->name('usuarios.toggle');
    Route::delete('/usuarios/{user}', [AuthController::class, 'destroyUsuario'])->name('usuarios.destroy');
    Route::get('/reportes', [AuthController::class, 'reportes'])->name('reportes');
    Route::get('/configuracion', [AuthController::class, 'configuracion'])->name('configuracion');
});
