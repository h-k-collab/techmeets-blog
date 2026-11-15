<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private PostService $postService,
    ) {}

    public function index()
    {
        return PostResource::collection($this->postRepository->getAll());
    }

    public function show(int $id)
    {
        return new PostResource($this->postRepository->findById($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => 'required|max:50',
        ]);

        // API はログインしないので、投稿者はテスト用ユーザーに固定する
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $validated['user_id'] = $user->id;

        $post = $this->postService->createPost($validated);

        return (new PostResource($post->load('user')))
            ->response()
            ->setStatusCode(201);
    }
}