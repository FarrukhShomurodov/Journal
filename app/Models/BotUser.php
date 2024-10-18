<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BotUser extends Model
{
    use HasFactory;


    /**
     * @var string[]
     */
    protected $fillable = [
        'chat_id',
        'first_name',
        'second_name',
        'uname',
        'phone',
        'step',
        'lang',
        'isactive',
        'country_id',
        'city_id',
        'last_activity',
    ];

    public function previousChoice(): HasOne
    {
        return $this->hasOne(BotUserPreviousChoice::class);
    }

    public function application(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function journey(): HasMany
    {
        return $this->hasMany(BotUserJourney::class);
    }

    public function session(): HasMany
    {
        return $this->hasMany(BotUserSession::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
