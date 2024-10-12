<?php

namespace App\Services;

use App\Models\DiseaseType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;


class DiseaseTypeService
{
    public function store(array $validated): Model|Builder
    {
        return DiseaseType::query()->create($validated);
    }

    public function update(DiseaseType $diseaseType, array $validated): DiseaseType
    {
        $diseaseType->update($validated);
        return $diseaseType->refresh();
    }

    public function destroy(DiseaseType $diseaseType): JsonResponse
    {
        $diseaseType->delete();
        return response()->json([], 201);
    }
}
