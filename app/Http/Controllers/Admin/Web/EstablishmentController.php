<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstablishmentRequest;
use App\Models\Category;
use App\Models\Establishment;
use App\Services\EstablishmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    protected EstablishmentService $establishmentService;

    public function __construct(EstablishmentService $establishmentService)
    {
        $this->establishmentService = $establishmentService;
    }

    public function index(): View
    {
        $establishments = Establishment::all();
        return view('admin.establishments.index', compact('establishments'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.establishments.create', compact('categories'));
    }

    public function store(EstablishmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->establishmentService->store($validated);
        return redirect()->route('establishments.index')->with('success', __('establishment.successfully_created'));
    }

    public function show(Establishment $establishment): View
    {
        return view('admin.establishments.show', compact('establishment'));
    }

    public function edit(Establishment $establishment): View
    {
        $categories = Category::all();
        return view('admin.establishments.edit', compact('establishment', 'categories'));
    }

    public function update(EstablishmentRequest $request, Establishment $establishment): RedirectResponse
    {
        $validated = $request->validated();
        $this->establishmentService->update($establishment, $validated);
        return redirect()->route('establishments.index')->with('success', __('establishment.successfully_updated'));
    }

    public function destroy(Establishment $establishment): RedirectResponse
    {
        $this->establishmentService->destroy($establishment);
        return redirect()->route('establishments.index')->with('success', __('establishment.successfully_deleted'));
    }
}
