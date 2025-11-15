<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'color',
        'plate_number',
    ];

    // deliveries module removed
    // public function deliveries() { }

    public function tankDeliveries()
    {
        return $this->hasMany(TankDelivery::class);
    }
}
