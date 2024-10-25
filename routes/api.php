<?php

use Illuminate\Support\Facades\Route;
use Ongoing\Sucursales\Http\Controllers\SucursalesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('sucursales')->group(function () {
    Route::get('/all', [SucursalesController::class, 'getAll'])->name('get_all_sucursales');
    Route::post('/save', [SucursalesController::class, 'save'])->name('save-sucursales');
    Route::post('/delete', [SucursalesController::class, 'delete'])->name('delete_sucursal');
});