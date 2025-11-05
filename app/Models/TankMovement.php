<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankMovement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tank_id',
        'previous_status',
        'new_status',
        'customer_id',
        'driver_id',
        'created_at',
    ];

    // ensure created_at is cast to a DateTime (Carbon) instance so ->format() works in views
    protected $dates = ['created_at'];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
