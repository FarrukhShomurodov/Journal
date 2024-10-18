<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    public function index(): View
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }


    public function create(): View
    {
        return view('admin.categories.create');
    }


    public function store(CategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->categoryService->store($validated);
        return redirect()->route('categories.index')->with('success', 'Категория успешно добавлена!');
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }


    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        $this->categoryService->update($category, $validated);
        return redirect()->route('categories.index')->with('success', 'Категория успешно обновлена!');
    }


    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->destroy($category);
        return redirect()->route('categories.index')->with('success', 'Категория успешно удалена!');
    }
}
