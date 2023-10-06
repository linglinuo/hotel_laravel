<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    public function roomMembers(): HasMany
    {
        return $this->hasMany(RoomMember::class);
    }
}
