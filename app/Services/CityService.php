<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CityService
{
    public function store(array $validated): Builder|Model
    {
        return City::query()->create($validated);
    }

    public function update(City $city, array $validated): City
    {
        $city->update($validated);
        return $city->refresh();
    }

    public function destroy(City $city): bool
    {
        $city->delete();
        return true;
    }
}
