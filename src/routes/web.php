<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);
Route::resource('products', ProductController::class);
Route::resource('events', EventController::class)->only(['index', 'show']);
Route::post('events/{event}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::resource('reservations', ReservationController::class)->only(['index', 'destroy']);
