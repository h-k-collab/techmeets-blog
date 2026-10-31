<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['event_id', 'name', 'email', 'people', 'reserved_at'];

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    // 1つの予約は、1つのイベントのもの
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
