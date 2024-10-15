<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotUserPreviousChoice extends Model
{
    use HasFactory;

    protected $table = 'bot_user_previous_choices';
    protected $fillable = [
        'bot_user_id',
        'previous_specialization_id',
        'previous_disease_type_id',
        'previous_clinic_id'
    ];

    public function previousSpecialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'previous_specialization_id', 'id');
    }

    public function previousDiseaseType(): BelongsTo
    {
        return $this->belongsTo(DiseaseType::class, 'previous_disease_type_id', 'id');
    }
}
