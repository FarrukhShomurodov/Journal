<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CountryController
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index(): View
    {
        $countries = Country::query()->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.countries.create');
    }

    public function store(CountryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->countryService->store($validated);
        return redirect()->route('countries.index')->with('success', 'Страна успешно добавлена!');
    }

    public function show(Country $country): View
    {
        return view('admin.countries.show', compact('country'));
    }


    public function edit(Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country): RedirectResponse
    {
        $validated = $request->validated();
        $this->countryService->update($country, $validated);
        return redirect()->route('countries.index')->with('success', 'Страна успешно обновлена!');
    }


    public function destroy(Country $country): RedirectResponse
    {
        $this->countryService->destroy($country);
        return redirect()->route('countries.index')->with('success', 'Страна успешно удалена!');
    }
}
