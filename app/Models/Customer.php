<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'address',
        'dropoff_street',
        'dropoff_city',
        'dropoff_landmark',
        'contact_number',
        'description',
        'dropoff_location',
        'reorder_point',
    ];

    /**
     * Return the customer's full name constructed from parts if available.
     */
    public function getFullNameAttribute(): string
    {
        // If explicit parts exist, build from them.
        $parts = [];
        if ($this->first_name) $parts[] = $this->first_name;
        if ($this->middle_name) $parts[] = $this->middle_name;
        if ($this->last_name) $parts[] = $this->last_name;

        if (!empty($parts)) {
            return trim(implode(' ', $parts));
        }

        // Fallback to legacy 'name' column
        return trim($this->name ?? '');
    }
}