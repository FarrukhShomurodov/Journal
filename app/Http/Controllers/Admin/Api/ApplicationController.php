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
}
