<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Establishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'working_hours',
        'price_from',
        'price_to',
        'category_id',
        'location_link',
        'contacts',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'contacts' => 'array',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
