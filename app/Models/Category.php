<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    use HasFactory;


    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }
}
