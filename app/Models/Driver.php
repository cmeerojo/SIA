<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_info',
        'license',
        'name',
        'contact_number',
    ];

    // deliveries module removed
    // public function deliveries() { }

    public function getFullNameAttribute(): string
    {
        $parts = [];
        if ($this->first_name) $parts[] = $this->first_name;
        if ($this->middle_name) $parts[] = $this->middle_name;
        if ($this->last_name) $parts[] = $this->last_name;

        if (!empty($parts)) {
            return trim(implode(' ', $parts));
        }

        return trim($this->name ?? '');
    }
}
