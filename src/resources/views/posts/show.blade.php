@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>カテゴリ:{{ $post->category }}</p>
    <p>投稿日:{{ $post->created_at->format('Y年m月d日') }}</p>

    <div>{!! nl2br(e($post->content)) !!}</div>

    <hr>

    <a href="{{ route('posts.index') }}">一覧へ戻る</a>
    <a href="{{ route('posts.edit', $post) }}">編集</a>

    <form action="{{ route('posts.destroy', $post) }}" method="POST"
          style="display:inline;"
          onsubmit="return confirm('本当に削除しますか?');">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>
@endsection