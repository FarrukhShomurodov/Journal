<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\Category;
use App\Models\Hotel;
use App\Services\HotelsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HotelController extends Controller
{
    protected HotelsService $hotelsService;

    public function __construct(HotelsService $hotelsService)
    {
        $this->hotelsService = $hotelsService;
    }

    public function index(): View
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.hotels.create', compact('categories'));
    }

    public function store(HotelRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->hotelsService->store($validated);
        return redirect()->route('hotels.index')->with('success', "Отель успешно добавлена!");
    }

    public function show(Hotel $hotel): View
    {
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel): View
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(HotelRequest $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validated();
        $this->hotelsService->update($hotel, $validated);
        return redirect()->route('hotels.index')->with('success', "Отель успешно обновлена!");
    }

    public function destroy(Hotel $hotel): RedirectResponse
    {
        $this->hotelsService->destroy($hotel);
        return redirect()->route('hotels.index')->with('success', "Отель успешно удалена!");
    }
}
