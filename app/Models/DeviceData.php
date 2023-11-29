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
        'trigger',
        'basic_element_id',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class,'device_id', 'device_id');
    }
    public function basic_element_id(): BelongsTo
    {
        return $this->belongsTo(BasicElement::class,'basic_element_id', 'basic_element_id');
    }
}
