<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BasicElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'board',
        'default',
        'small_marks',
        'type',
        'default_value',
        'value',
    ];
    public function device(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
