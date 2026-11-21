<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService,
        private PostRepository $postRepository
    ) {}

    // 一覧
    public function index()
    {
        $posts = $this->postRepository->getPublished();
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
            'image'    => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image_path'] = Storage::disk('s3')->put('images', $request->file('image'));
        }
        unset($validated['image']);

        $post = $this->postService->createPost($validated);

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
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    // 更新の保存
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title'    => 'required|max:200',
            'content'  => 'required',
            'category' => 'required|max:50',
        ]);

        $post = $this->postService->updatePost($post->id, $validated);

        return redirect()->route('posts.show', $post)
            ->with('success', '投稿を更新しました');
    }

    // 削除
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $this->postRepository->delete($post);

        return redirect()->route('posts.index')
            ->with('success', '投稿を削除しました');
    }
}