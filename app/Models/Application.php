<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'clinic_id',
        'text'
    ];

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }
}
