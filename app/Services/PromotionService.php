<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PromotionService
{
    public function store(array $validated): Model|Builder
    {
        $promotion = Promotion::query()->create($validated);

        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('promotion_photos', 'public');
                $promotion->images()->create(['url' => $path]);
            }
        }

        return $promotion;

    }

    public function update(Promotion $promotion, array $validated): Promotion
    {
        $promotion->update($validated);


        if (isset($validated['photos'])) {
            foreach ($validated['photos'] as $photo) {
                $path = $photo->store('promotion_photos', 'public');
                $promotion->images()->create(['url' => $path]);
            }
        }

        return $promotion->refresh();
    }

    public function destroy(Promotion $promotion): JsonResponse
    {
        foreach ($promotion->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $promotion->delete();
        return response()->json([], 201);
    }
}
