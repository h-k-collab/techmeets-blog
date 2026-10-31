<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 一覧(10件ずつ)
    public function index()
    {
        $posts = Post::latest()->latest('id')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    // 作成フォーム
    public function create()
    {
        return view('posts.create');
    }

    // 作成の保存
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|max:200',
            'content'  => 'required',
            'category' => 'required|max:50',
        ]);

        $post = Post::create($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', '投稿を作成しました');
    }

    // 詳細
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // 編集フォーム
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // 更新の保存
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'    => 'required|max:200',
            'content'  => 'required',
            'category' => 'required|max:50',
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', '投稿を更新しました');
    }

    // 削除
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', '投稿を削除しました');
    }
}