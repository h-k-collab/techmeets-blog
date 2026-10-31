@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>

    <p>価格:{{ number_format($product->price) }}円</p>
    <p>在庫:{{ $product->stock }}</p>
    <p>カテゴリ:{{ $product->category }}</p>
    <div>説明:{!! nl2br(e($product->description)) !!}</div>

    <hr>

    <a href="{{ route('products.index') }}">一覧へ戻る</a>
    <a href="{{ route('products.edit', $product) }}">編集</a>

    <form action="{{ route('products.destroy', $product) }}" method="POST"
          style="display:inline;"
          onsubmit="return confirm('本当に削除しますか?');">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>
@endsection