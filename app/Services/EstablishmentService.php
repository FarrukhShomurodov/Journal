<?php

namespace App\Services;

use App\Models\Establishment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EstablishmentService
{
    public function store(array $validated): Model|Builder
    {
        $establishment = Establishment::query()->create($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('establishment_photos', 'public');

                $establishment->images()->create(['url' => $path]);
            }
        }

        return $establishment;
    }

    public function update(Establishment $establishment, array $validated): Establishment
    {
        $establishment->update($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('establishment_photos', 'public');

                $establishment->images()->create(['url' => $path]);
            }
        }

        return $establishment->refresh();
    }

    public function destroy(Establishment $establishment): JsonResponse
    {
        foreach ($establishment->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $establishment->delete();
        return response()->json([], 201);
    }
}
