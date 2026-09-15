<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

// Admin CRUD Menu
Route::resource('menus', MenuController::class);

// Pelanggan (Self-Service Kiosk)
Route::get('/', [OrderController::class, 'kiosk'])->name('kiosk');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/struk/{id}', [OrderController::class, 'struk'])->name('order.struk');

// Kasir & Pemanggilan Suara
Route::get('/kasir', [OrderController::class, 'kasir'])->name('kasir.index');
Route::get('/kasir/status/{id}/{status}', [OrderController::class, 'updateStatus'])->name('kasir.status');