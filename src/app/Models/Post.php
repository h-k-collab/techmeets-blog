<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'category', 'user_id', 'image_path'];

    // 1つの投稿に、たくさんのコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 投稿の作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}