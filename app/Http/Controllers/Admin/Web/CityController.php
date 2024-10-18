<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Country;
use App\Services\CityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CityController extends Controller
{
    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index(): View
    {
        $cities = City::query()->get();
        return view('admin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        $countries = Country::query()->get();
        return view('admin.cities.create', compact('countries'));
    }

    public function store(CityRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->cityService->store($validated);
        return redirect()->route('cities.index')->with('success', 'Город успешно добавлена!');
    }

    public function edit(City $city): View
    {
        $countries = Country::query()->get();
        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(CityRequest $request, City $city): RedirectResponse
    {
        $validated = $request->validated();
        $this->cityService->update($city, $validated);
        return redirect()->route('cities.index')->with('success', 'Город успешно обновлена!');
    }


    public function destroy(City $city): RedirectResponse
    {
        $this->cityService->destroy($city);
        return redirect()->route('cities.index')->with('success', 'Город успешно удалена!');
    }
}
