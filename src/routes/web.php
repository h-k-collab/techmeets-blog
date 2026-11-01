<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

// ログインが必要なルートをまとめてグループ化
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('products', ProductController::class);
Route::resource('events', EventController::class)->only(['index', 'show']);
Route::post('events/{event}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::resource('reservations', ReservationController::class)->only(['index', 'destroy']);
require __DIR__.'/auth.php';
