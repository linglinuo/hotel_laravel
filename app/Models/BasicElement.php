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
        'small_marks_date',
        'small_marks_time',
        'small_marks_people',
        'small_marks_other',
        'on_name',
        'off_name',
        'type',
        'switches',
    ];
    public function device(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
