<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'category', 'user_id'];

    // 1つの投稿に、たくさんのコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}