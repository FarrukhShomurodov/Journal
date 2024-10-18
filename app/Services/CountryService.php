<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CountryService
{
    public function store(array $validated): Model|Builder
    {
        return Country::query()->create($validated);
    }

    public function update(Country $country, array $validated): Country
    {
        $country->update($validated);
        return $country->refresh();
    }

    public function destroy(Country $country): bool
    {
        $country->delete();
        return true;
    }
}
