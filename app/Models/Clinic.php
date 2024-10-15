<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'working_hours',
        'location_link',
        'contacts',
        'rating',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'contacts' => 'array',
    ];


    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function diseaseTypes(): BelongsToMany
    {
        return $this->belongsToMany(DiseaseType::class, 'clinic_disease_type');
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'clinic_specialization');
    }

    /**
     * Scope a query to sort clinics by rating.
     *
     * @param Builder $query
     * @param string $order
     * @return Builder
     */
    public function scopeOrderByRating($query, $order = 'desc'): Builder
    {
        return $query->orderBy('rating', $order);
    }

}
