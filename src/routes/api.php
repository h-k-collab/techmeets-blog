<?php

// Week 10 で作った、API用のPostController(既存)
use App\Http\Controllers\Api\PostController;
// Week 12 で作った、Stripe Webhook用のコントローラー(追加)
use App\Http\Controllers\StripeWebhookController;
// ログイン中のユーザー情報を取るために使うクラス(既存)
use Illuminate\Http\Request;
// ルートを定義するためのクラス(既存)
use Illuminate\Support\Facades\Route;

// ログイン中のユーザー情報を返すAPI(既存)。Sanctumの認証が必要
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 投稿の一覧を返すAPI(既存)
Route::get('/posts', [PostController::class, 'index']);
// 投稿を作成するAPI(既存)
Route::post('/posts', [PostController::class, 'store']);
// 投稿1件を返すAPI(既存)
Route::get('/posts/{id}', [PostController::class, 'show']);

// StripeからのWebhookを受け取るAPI(追加)。認証は付けず、署名の検証で本物かを確かめる
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle']);