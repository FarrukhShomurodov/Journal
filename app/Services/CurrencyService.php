<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CurrencyService
{
    public function store(array $validated): Model|Builder
    {
        return Currency::query()->create($validated);
    }
}
