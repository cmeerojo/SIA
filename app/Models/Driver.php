<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_info',
        'license',
        'name',
        'contact_number',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
