<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use App\Services\PromotionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromotionController extends Controller
{
    protected PromotionService $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index(): View
    {
        $promotions = Promotion::all();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        return view('admin.promotions.create');
    }

    public function store(PromotionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->promotionService->store($validated);
        return redirect()->route('promotions.index')->with('success', 'Акция успешно добавлена!');
    }

    public function show(Promotion $promotion): View
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(PromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $validated = $request->validated();
        $this->promotionService->update($promotion, $validated);
        return redirect()->route('promotions.index')->with('success', 'Акция успешно обновлена!');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $this->promotionService->destroy($promotion);
        return redirect()->route('promotions.index')->with('success', 'Акция успешно удалена!');
    }
}
