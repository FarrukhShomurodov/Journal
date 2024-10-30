<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Currency extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
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

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }
}
