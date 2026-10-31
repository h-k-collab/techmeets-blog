@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <h1>商品一覧</h1>

    <p><a href="{{ route('products.create') }}">新規登録</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫</th>
            <th>カテゴリ</th>
            <th></th>
        </tr>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price) }}円</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->category }}</td>
                <td><a href="{{ route('products.show', $product) }}">詳細</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="5">まだ商品がありません。</td>
            </tr>
        @endforelse
    </table>

    {{ $products->links() }}
@endsection