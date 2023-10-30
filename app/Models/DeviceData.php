<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceData extends Model
{
    use HasFactory;
    public $table = "device_datas";

    protected $fillable = [
        'device_id',
        'temp',
        'humidity',
        'ctrl_cmd',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class,'device_id', 'device_id');
    }
}
