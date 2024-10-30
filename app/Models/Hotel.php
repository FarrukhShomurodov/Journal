<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hotel extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'working_hours',
        'price_from',
        'price_to',
        'location_link',
        'contacts',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'working_hours' => 'string',
        'contacts' => 'array',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }
}
