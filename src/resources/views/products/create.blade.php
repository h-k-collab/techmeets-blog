@extends('layouts.app')

@section('title', '商品登録')

@section('content')
    <h1>商品登録</h1>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <p>
            <label>商品名</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </p>
        <p>
            <label>価格(円)</label><br>
            <input type="number" name="price" min="0" value="{{ old('price') }}">
        </p>
        <p>
            <label>在庫</label><br>
            <input type="number" name="stock" min="0" value="{{ old('stock', 0) }}">
        </p>
        <p>
            <label>カテゴリ</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
        </p>
        <p>
            <label>説明(任意)</label><br>
            <textarea name="description" rows="5">{{ old('description') }}</textarea>
        </p>

        <button type="submit">登録する</button>
        <a href="{{ route('products.index') }}">キャンセル</a>
    </form>
@endsection