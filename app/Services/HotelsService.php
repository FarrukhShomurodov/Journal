<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HotelsService
{
    public function store(array $validated): Model|Builder
    {
        $hotel = Hotel::query()->create($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('hotel_photos', 'public');

                $hotel->images()->create(['url' => $path]);
            }
        }
        return $hotel;
    }

    public function update(Hotel $hotel, array $validated): Hotel
    {
        $hotel->update($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('hotel_photos', 'public');

                $hotel->images()->create(['url' => $path]);
            }
        }

        return $hotel->refresh();
    }

    public function destroy(Hotel $hotel): JsonResponse
    {
        foreach ($hotel->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $hotel->delete();
        return response()->json([], 201);
    }

}
