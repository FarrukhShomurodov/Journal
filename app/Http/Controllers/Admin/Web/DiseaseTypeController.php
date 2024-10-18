<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiseaseTypeRequest;
use App\Models\DiseaseType;
use App\Services\DiseaseTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiseaseTypeController extends Controller
{
    protected DiseaseTypeService $diseaseTypeService;

    public function __construct(DiseaseTypeService $diseaseTypeService)
    {
        $this->diseaseTypeService = $diseaseTypeService;
    }

    public function index(): View
    {
        $diseaseTypes = DiseaseType::all();
        return view('admin.diseaseTypes.index', compact('diseaseTypes'));
    }

    public function create(): View
    {
        return view('admin.diseaseTypes.create');
    }

    public function store(DiseaseTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->diseaseTypeService->store($validated);
        return redirect()->route('diseaseTypes.index')->with('success', 'Тип болезни успешно добавлен!');
    }

    public function show(DiseaseType $diseaseType): View
    {
        return view('admin.diseaseTypes.show', compact('diseaseType'));
    }

    public function edit(DiseaseType $diseaseType): View
    {
        return view('admin.diseaseTypes.edit', compact('diseaseType'));
    }

    public function update(DiseaseTypeRequest $request, DiseaseType $diseaseType): RedirectResponse
    {
        $validated = $request->validated();
        $this->diseaseTypeService->update($diseaseType, $validated);
        return redirect()->route('diseaseTypes.index')->with('success', 'Тип болезни успешно обновлена!');
    }

    public function destroy(DiseaseType $diseaseType): RedirectResponse
    {
        $this->diseaseTypeService->destroy($diseaseType);
        return redirect()->route('diseaseTypes.index')->with('success', 'Тип болезни успешно удалена!');
    }
}
