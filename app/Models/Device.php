<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'room_id',
        'basic_element_id',
        'created',
    ];

    public function deviceData(): HasMany
    {
        return $this->hasMany(DeviceData::class,'device_id', 'device_id');
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function basic_element_id(): BelongsTo
    {
        return $this->belongsTo(BasicElement::class,'basic_element_id', 'basic_element_id');
    }
}
