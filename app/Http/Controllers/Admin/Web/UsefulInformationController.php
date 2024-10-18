<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsefulInformationRequest;
use App\Models\UsefulInformation;
use App\Services\UsefulInformationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsefulInformationController extends Controller
{
    protected UsefulInformationService $informationService;

    public function __construct(UsefulInformationService $informationService)
    {
        $this->informationService = $informationService;
    }

    public function index(): View
    {
        $usefulInfos = UsefulInformation::all();
        return view('admin.usefulInfos.index', compact('usefulInfos'));
    }

    public function create(): View
    {
        return view('admin.usefulInfos.create');
    }

    public function store(UsefulInformationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->informationService->store($validated);
        return redirect()->route('usefulInfos.index')->with('success', 'Полезная информация  успешно добавлена!');
    }

    public function show(UsefulInformationRequest $usefulInfo): View
    {
        return view('admin.usefulInfos.show', compact('usefulInfo'));
    }

    public function edit(UsefulInformation $usefulInfo): View
    {
        return view('admin.usefulInfos.edit', compact('usefulInfo'));
    }

    public function update(UsefulInformationRequest $request, UsefulInformation $usefulInfo): RedirectResponse
    {
        $validated = $request->validated();
        $this->informationService->update($usefulInfo, $validated);
        return redirect()->route('usefulInfos.index')->with('success', 'Полезная информация  успешно обновлена!');
    }

    public function destroy(UsefulInformation $usefulInfo): RedirectResponse
    {
        $this->informationService->destroy($usefulInfo);
        return redirect()->route('usefulInfos.index')->with('success', 'Полезная информация  успешно удалена!');
    }
}
