<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TankDelivery extends Model
{
    use HasFactory;

    protected $table = 'tank_deliveries';

    protected $fillable = [
        'tank_id',
        'customer_id',
        'driver_id',
        'date_delivered',
    ];

    protected $dates = ['date_delivered'];
    protected $casts = [
        'date_delivered' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
