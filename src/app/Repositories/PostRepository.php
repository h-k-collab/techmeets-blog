<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    // 全件取得（最新順・ページネーション付き）
    public function getAll()
    {
        return Post::latest()->paginate(10);
    }

    // IDで1件取得
    public function findById(int $id)
    {
        return Post::findOrFail($id);
    }

    // 一覧表示用の投稿を取得（最新順・ページネーション付き）
    // Week 7・8 の posts には公開状態の列がないため、ここでは条件をつけずに取得する
    // （status 列を追加したら、ここに ->where('status', 'published') を足すだけで済む）
    public function getPublished()
    {
        return Post::latest()->paginate(10);
    }

    // 特定ユーザーの投稿を取得
    public function getByUser(int $userId)
    {
        return Post::where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post)
    {
        return $post->delete();
    }
}