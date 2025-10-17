<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['brand', 'size', 'amount', 'is_hidden', 'valve_type'];

    protected $casts = [
    'is_hidden' => 'boolean',
    ];
}