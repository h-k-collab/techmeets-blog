@extends('layouts.app')

@section('title', '新規投稿')

@section('content')
    <h1>新規投稿</h1>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <label for="title">タイトル</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" maxlength="200">

        <label for="category">カテゴリ</label>
        <input type="text" id="category" name="category" value="{{ old('category') }}" maxlength="50">

        <label for="content">本文</label>
        <textarea id="content" name="content" rows="10">{{ old('content') }}</textarea>

        <button type="submit" class="btn">投稿する</button>
    </form>
@endsection
