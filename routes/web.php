<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControllerAutos;
use App\Http\Controllers\ControllerConductores;
use App\Http\Controllers\ControllerConfiguracion;
use App\Http\Controllers\ControllerReportes;
use App\Http\Controllers\ControllerUsuarios;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/autos', [ControllerAutos::class, 'autos'])->name('autos');
    Route::post('/autos', [ControllerAutos::class, 'storeAuto'])->name('autos.store');
    Route::get('/conductores', [ControllerConductores::class, 'conductores'])->name('conductores');
    Route::post('/conductores', [ControllerConductores::class, 'storeConductor'])->name('conductores.store');
    Route::put('/conductores/{conductor}', [ControllerConductores::class, 'updateConductor'])->name('conductores.update');
    Route::delete('/conductores/{conductor}', [ControllerConductores::class, 'destroyConductor'])->name('conductores.destroy');
    Route::get('/usuarios', [ControllerUsuarios::class, 'usuarios'])->name('usuarios');
    Route::post('/usuarios', [ControllerUsuarios::class, 'storeUsuario'])->name('usuarios.store');
    Route::post('/usuarios/{user}/toggle', [ControllerUsuarios::class, 'toggleUsuario'])->name('usuarios.toggle');
    Route::delete('/usuarios/{user}', [ControllerUsuarios::class, 'destroyUsuario'])->name('usuarios.destroy');
    Route::get('/reportes', [ControllerReportes::class, 'reportes'])->name('reportes');
    Route::get('/configuracion', [ControllerConfiguracion::class, 'configuracion'])->name('configuracion');
});
