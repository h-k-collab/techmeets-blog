@extends('layouts.app')

@section('title', '投稿一覧')

@section('content')
    <h1>投稿一覧</h1>

    @forelse ($posts as $post)
        <div class="card">
            <h2><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h2>
            <p>カテゴリ：{{ $post->category }} ／ 投稿日：{{ $post->created_at->format('Y-m-d H:i') }}</p>
            <p>{{ Str::limit($post->content, 100) }}</p>
        </div>
    @empty
        <p>まだ投稿がありません。</p>
    @endforelse

    {{ $posts->links('pagination::default') }}
@endsection
