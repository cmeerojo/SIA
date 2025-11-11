<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'dropoff_location',
        'driver_id',
        'item_id',
        'quantity',
        'status',
        'driver_latitude',
        'driver_longitude',
        'driver_location_updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
