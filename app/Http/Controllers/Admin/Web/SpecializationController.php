<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecializationRequest;
use App\Models\Specialization;
use App\Services\SpecializationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpecializationController extends Controller
{
    protected SpecializationService $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    public function index(): View
    {
        $specializations = Specialization::all();
        return view('admin.specializations.index', compact('specializations'));
    }

    public function create(): View
    {
        return view('admin.specializations.create');
    }

    public function store(SpecializationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->specializationService->store($validated);
        return redirect()->route('specializations.index')->with('success', __('specialization.successfully_created'));
    }

    public function show(Specialization $specialization): View
    {
        return view('admin.specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization): View
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(SpecializationRequest $request, Specialization $specialization): RedirectResponse
    {
        $validated = $request->validated();
        $this->specializationService->update($specialization, $validated);
        return redirect()->route('specializations.index')->with('success', __('specialization.successfully_created'));
    }

    public function destroy(Specialization $specialization): RedirectResponse
    {
        $this->specializationService->destroy($specialization);
        return redirect()->route('specializations.index')->with('success', __('specialization.successfully_created'));
    }
}
