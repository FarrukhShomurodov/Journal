<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ClinicService
{
    public function store(array $validated): Model|Builder
    {
        $clinic = Clinic::query()->create($validated);

        if (isset($validated['disease_type'])) {
            $clinic->diseaseTypes()->sync($validated['disease_type']);
        }

        if (isset($validated['specialization'])) {
            $clinic->specializations()->sync($validated['specialization']);
        }


        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('clinic_photos', 'public');
                $clinic->images()->create(['url' => $path]);
            }
        }

        return $clinic;
    }

    public function update(Clinic $clinic, array $validated): Clinic
    {
        $clinic->update($validated);

        if (isset($validated['disease_type'])) {
            $clinic->diseaseTypes()->sync($validated['disease_type']);
        }

        if (isset($validated['specialization'])) {
            $clinic->specializations()->sync($validated['specialization']);
        }


        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('clinic_photos', 'public');
                $clinic->images()->create(['url' => $path]);
            }
        }

        return $clinic->refresh();
    }

    public function destroy(Clinic $clinic): JsonResponse
    {
        foreach ($clinic->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $clinic->delete();
        return response()->json([], 201);
    }
}
