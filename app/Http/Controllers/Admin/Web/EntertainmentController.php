<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntertainmentRequest;
use App\Models\Entertainment;
use App\Services\EntertainmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntertainmentController extends Controller
{
    protected EntertainmentService $entertainmentService;

    public function __construct(EntertainmentService $entertainmentService)
    {
        $this->entertainmentService = $entertainmentService;
    }

    public function index(): View
    {
        $entertainments = Entertainment::all();
        return view('admin.entertainments.index', compact('entertainments'));
    }

    public function create(): View
    {
        return view('admin.entertainments.create');
    }

    public function store(EntertainmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->entertainmentService->store($validated);
        return redirect()->route('entertainments.index')->with('success', __('entertainment.successfully_created'));
    }

    public function show(Entertainment $entertainment): View
    {
        return view('admin.entertainments.show', compact('entertainment'));
    }

    public function edit(Entertainment $entertainment): View
    {
        return view('admin.entertainments.edit', compact('entertainment'));
    }

    public function update(EntertainmentRequest $request, Entertainment $entertainment): RedirectResponse
    {
        $validated = $request->validated();
        $this->entertainmentService->update($entertainment, $validated);
        return redirect()->route('entertainments.index')->with('success', __('entertainment.successfully_updated'));
    }

    public function destroy(Entertainment $entertainment): RedirectResponse
    {
        $this->entertainmentService->destroy($entertainment);
        return redirect()->route('entertainments.index')->with('success', __('entertainment.successfully_deleted'));
    }
}
