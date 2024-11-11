<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeticionController;
use App\Http\Controllers\ContribuyenteController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Rutas para el controlador Peticion
Route::prefix('peticiones')->group(function () {
    Route::get('/', [PeticionController::class, 'index'])->name('peticiones.index');
    Route::get('/create', [PeticionController::class, 'create'])->name('peticiones.create');
    Route::post('/', [PeticionController::class, 'store'])->name('peticiones.store');
    Route::get('/{id}', [PeticionController::class, 'show'])->name('peticiones.show');
    Route::get('/{id}/edit', [PeticionController::class, 'edit'])->name('peticiones.edit');
    Route::put('/{id}', [PeticionController::class, 'update'])->name('peticiones.update');
    Route::delete('/{id}', [PeticionController::class, 'destroy'])->name('peticiones.destroy');
    Route::get('/buscar', [PeticionController::class, 'buscar'])->name('peticiones.buscar');
});

// Rutas para el controlador Contribuyente
Route::prefix('contribuyentes')->group(function () {
    Route::get('/', [ContribuyenteController::class, 'index'])->name('contribuyentes.index');
    Route::get('/create', [ContribuyenteController::class, 'create'])->name('contribuyentes.create');
    Route::post('/', [ContribuyenteController::class, 'store'])->name('contribuyentes.store');
    Route::get('/{id}', [ContribuyenteController::class, 'show'])->name('contribuyentes.show');
    Route::get('/{id}/edit', [ContribuyenteController::class, 'edit'])->name('contribuyentes.edit');
    Route::put('/{id}', [ContribuyenteController::class, 'update'])->name('contribuyentes.update');
    Route::delete('/{id}', [ContribuyenteController::class, 'destroy'])->name('contribuyentes.destroy');
    Route::get('/buscar', [ContribuyenteController::class, 'buscar'])->name('contribuyentes.buscar');
});

// Rutas para el controlador Asignacion
Route::prefix('asignaciones')->group(function () {
    Route::get('/', [AsignacionController::class, 'index'])->name('asignaciones.index');
    Route::get('/create', [AsignacionController::class, 'create'])->name('asignaciones.create');
    Route::post('/', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::get('/{id}', [AsignacionController::class, 'show'])->name('asignaciones.show');
    Route::get('/{id}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');
    Route::put('/{id}', [AsignacionController::class, 'update'])->name('asignaciones.update');
    Route::delete('/{id}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');
    Route::get('/buscar', [AsignacionController::class, 'buscar'])->name('asignaciones.buscar');
});

// Rutas para el controlador Funcionario
Route::prefix('funcionarios')->group(function () {
    Route::get('/', [FuncionarioController::class, 'index'])->name('funcionarios.index');
    Route::get('/create', [FuncionarioController::class, 'create'])->name('funcionarios.create');
    Route::post('/', [FuncionarioController::class, 'store'])->name('funcionarios.store');
    Route::get('/{id}', [FuncionarioController::class, 'show'])->name('funcionarios.show');
    Route::get('/{id}/edit', [FuncionarioController::class, 'edit'])->name('funcionarios.edit');
    Route::put('/{id}', [FuncionarioController::class, 'update'])->name('funcionarios.update');
    Route::delete('/{id}', [FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');
    Route::get('/buscar', [FuncionarioController::class, 'buscar'])->name('funcionarios.buscar');
});

// Rutas para el controlador User
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/buscar', [UserController::class, 'buscar'])->name('users.buscar');
});
