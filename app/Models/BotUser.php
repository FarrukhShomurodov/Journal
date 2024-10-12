<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'first_name',
        'second_name',
        'uname',
        'typed_name',
        'phone',
        'sms_code',
        'step',
        'lang',
        'isactive'
    ];

    public function stepInformation(): HasOne
    {
        return $this->hasOne(BotUserStepInformation::class);
    }

    public function application(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
