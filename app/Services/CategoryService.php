<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CategoryService
{
    public function store(array $validated): Model|Builder
    {
        return Category::query()->create($validated);
    }

    public function update(Category $category, array $validated): Category
    {
        $category->update($validated);
        return $category->refresh();
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json([], 201);
    }
}
