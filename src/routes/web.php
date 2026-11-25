<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
// 応援(投げ銭)の処理を担当するコントローラーを使えるようにする
use App\Http\Controllers\SupportController;

Route::get('/', function () {
    return view('welcome');
});

// ログインが必要なルートをまとめてグループ化
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class)->except(['index', 'show']);

    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // 「応援する」ボタンの送信先。Stripe の決済ページを作って、そこに移動させる({post} は、どの記事か)
    Route::post('posts/{post}/support', [SupportController::class, 'create'])->name('supports.create');
    // 決済のあとに、Stripe から戻ってくるページ。決済の確認と、応援履歴の保存をする
    Route::get('supports/success', [SupportController::class, 'success'])->name('supports.success');

    Route::resource('products', ProductController::class);
    Route::resource('reservations', ReservationController::class)->only(['index', 'destroy']);
});
Route::resource('posts', PostController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});




Route::resource('events', EventController::class)->only(['index', 'show']);
Route::post('events/{event}/reservations', [ReservationController::class, 'store'])->name('reservations.store');

require __DIR__.'/auth.php';