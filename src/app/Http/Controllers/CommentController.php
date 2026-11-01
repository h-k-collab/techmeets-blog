<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // コメントの投稿
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(), // ログイン中の人を書き手として記録
            'content' => $validated['content'],
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'コメントを投稿しました');
    }

    // コメントの削除(自分のコメントだけ)
    public function destroy(Comment $comment)
    {
        if ($comment->user_id != auth()->id()) {
            abort(403, 'この操作は許可されていません');
        }

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('posts.show', $post)
            ->with('success', 'コメントを削除しました');
    }
}
