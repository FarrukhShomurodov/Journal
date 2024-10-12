<?php

namespace App\Services;

use App\Models\Specialization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class SpecializationService
{
    public function store(array $validated): Model|Builder
    {
        return Specialization::query()->create($validated);
    }

    public function update(Specialization $specialization, array $validated): Specialization
    {
        $specialization->update($validated);
        return $specialization->refresh();
    }

    public function destroy(Specialization $specialization): JsonResponse
    {
        $specialization->delete();
        return response()->json([], 201);
    }
}
