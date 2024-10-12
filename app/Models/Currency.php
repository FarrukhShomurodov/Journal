<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'ccy',
        'rate',
        'relevance_date',
    ];

    protected $casts = [
        'name' => 'array',
        'relevance_date' => 'date',
        'rate' => 'decimal:2',
    ];
}
