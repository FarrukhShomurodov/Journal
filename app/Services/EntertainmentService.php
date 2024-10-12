<?php

namespace App\Services;

use App\Models\Entertainment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EntertainmentService
{
    public function store(array $validated): Model|Builder
    {
        $entertainment = Entertainment::query()->create($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('entertainment_photos', 'public');
                $entertainment->images()->create(['url' => $path]);
            }
        }

        return $entertainment;
    }

    public function update(Entertainment $entertainment, array $validated): Entertainment
    {
        $entertainment->update($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('entertainment_photos', 'public');
                $entertainment->images()->create(['url' => $path]);
            }
        }

        return $entertainment->refresh();
    }

    public function destroy(Entertainment $entertainment): JsonResponse
    {
        foreach ($entertainment->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $entertainment->delete();
        return response()->json([], 201);
    }
}
