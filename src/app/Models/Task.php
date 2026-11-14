<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'title', 'due_date', 'is_completed'];

    protected $casts = [
        'due_date'     => 'date',
        'is_completed' => 'boolean',
    ];

    // このタスクの持ち主
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}