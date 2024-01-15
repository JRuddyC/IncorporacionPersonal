<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ImportarExcelController;
use App\Http\Controllers\ImportarImagesController;
use App\Http\Controllers\IncorporacionesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/users', [ViewController::class,'users'])->name('users');
    Route::get('/roles', [ViewController::class,'roles'])->name('roles');
    Route::get('/permissions', [ViewController::class,'permissions'])->name('permissions');
    Route::get('/imagen-persona/{personaId}', [ImportarImagesController::class, 'getImagenPersona'])->name('imagen-persona');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/migraciones', function () {
    return Inertia::render('Migraciones/Index');
})->name('migraciones');

Route::middleware(['auth:sanctum', 'verified'])->get('/incorporaciones', function () {
    return Inertia::render('Incorporaciones/Index');
})->name('incorporaciones');
