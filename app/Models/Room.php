<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'room_type',
        'status',
        'price'
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
}
