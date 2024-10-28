<?php
use Illuminate\Support\Facades\Route;
use Ongoing\Sucursales\Http\Controllers\SucursalesController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', [SucursalesController::class, 'index'])->name('sucursales.list');
});