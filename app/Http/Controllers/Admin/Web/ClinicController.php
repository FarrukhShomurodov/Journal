<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Models\Clinic;
use App\Models\DiseaseType;
use App\Models\Specialization;
use App\Services\ClinicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClinicController extends Controller
{
    protected ClinicService $clinicService;

    public function __construct(ClinicService $clinicService)
    {
        $this->clinicService = $clinicService;
    }

    public function index(): View
    {
        $clinics = Clinic::all();
        return view('admin.clinics.index', compact('clinics'));
    }

    public function create(): View
    {
        $specializations = Specialization::all();
        $diseaseTypes = DiseaseType::all();
        return view('admin.clinics.create', compact('specializations', 'diseaseTypes'));
    }

    public function store(ClinicRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->clinicService->store($validated);
        return redirect()->route('clinics.index')->with('success', __('clinic.successfully_created'));
    }

    public function edit(Clinic $clinic): View
    {
        $specializations = Specialization::all();
        $diseaseTypes = DiseaseType::all();
        return view('admin.clinics.edit', compact('clinic', 'specializations', 'diseaseTypes'));
    }

    public function update(ClinicRequest $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validated();
        $this->clinicService->update($clinic, $validated);
        return redirect()->route('clinics.index')->with('success', __('clinic.successfully_created'));
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        $this->clinicService->destroy($clinic);
        return redirect()->route('clinics.index')->with('success', __('clinic.successfully_created'));
    }
}
