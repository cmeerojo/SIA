<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_code',
        'status', // filled, empty, with_customer
        'brand',
        'valve_type',
        'size',
        'amount',
        'is_hidden',
    ];

    public function movements()
    {
        return $this->hasMany(TankMovement::class);
    }

    public function deliveries()
    {
        return $this->hasMany(TankDelivery::class);
    }
}
