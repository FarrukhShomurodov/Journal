<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUserJourney extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'bot_user_id',
        'event_name',
    ];
}
