<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function isReviewed(Request $request, Application $application): JsonResponse
    {
        $validated = $request->validate(['is_reviewed' => 'required|boolean']);

        $this->applicationService->isReviewed($application, $validated);

        return response()->json([$application], 200);
    }

    public function filterApplications(Request $request): JsonResponse
    {
        $clinicId = $request->input('clinic-id');
        $specializationId = $request->input('specialization-id');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $phone = $request->input('phone');

        $query = Application::query()->orderBy('id', 'asc');

        if ($clinicId && $clinicId !== 'all') {
            $query->where('clinic_id', $clinicId);
        }

        if ($specializationId && $specializationId !== 'all') {
            $query->whereHas('clinic.specializations', function ($q) use ($specializationId) {
                $q->where('specializations.id', $specializationId);
            });
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($phone) {
            $query->whereHas('botUser', function ($q) use ($phone) {
                $q->where('phone', 'LIKE', "%{$phone}%");
            });
        }

        $applications = $query->with(['botUser', 'clinic'])->get();

        return response()->json($applications);
    }
}
