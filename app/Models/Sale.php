<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tank_id', // Keep for backward compatibility, but we'll use tanks() relationship
        'quantity',
        'price',
        'payment_method',
        'status',
        'transaction_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function tanks()
    {
        return $this->belongsToMany(Tank::class, 'sale_tanks')->withTimestamps();
    }
}
