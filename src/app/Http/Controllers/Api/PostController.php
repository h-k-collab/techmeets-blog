<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository
    ) {}

    // 一覧
    public function index()
    {
        $posts = $this->postRepository->getAll();
        return PostResource::collection($posts);
    }

    // 1件取得
    public function show(int $id)
    {
        $post = $this->postRepository->findById($id);
        return new PostResource($post);
    }
}