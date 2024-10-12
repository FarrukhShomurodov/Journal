<?php

namespace App\Services;

use App\Models\UsefulInformation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UsefulInformationService
{
    public function store(array $validated): Model|Builder
    {
        $information = UsefulInformation::query()->create($validated);
        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('information_photos', 'public');
                $information->images()->create(['url' => $path]);
            }
        }
        return $information;
    }

    public function update(UsefulInformation $information, array $validated): UsefulInformation
    {
        $information->update($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('information_photos', 'public');
                $information->images()->create(['url' => $path]);
            }
        }
        return $information->refresh();
    }

    public function destroy(UsefulInformation $information): JsonResponse
    {
        foreach ($information->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $information->delete();
        return response()->json([], 201);
    }
}
