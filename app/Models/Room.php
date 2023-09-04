<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'type',
        'room_name',
        'status',
        'email',
        'photo',
        'info',
        'members'
    ];
}
