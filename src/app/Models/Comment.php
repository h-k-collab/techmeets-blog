<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content'];

    // コメントを書いた人
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントが付いている投稿
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
