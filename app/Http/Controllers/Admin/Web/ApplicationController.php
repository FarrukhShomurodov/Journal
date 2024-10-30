<?php

namespace App\Http\Controllers\Admin\Web;

use App\Models\Application;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController
{
    public function index(Request $request): View
    {
        $clinics = Clinic::query()->orderBy('id', 'asc')->get();

        $clinicId = $request->input('clinic-id');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $query = Application::query()->orderBy('id', 'asc');

        if ($clinicId && $clinicId !== 'all') {
            $query->where('clinic_id', $clinicId);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $applications = $query->get();

        return view('admin.applications.index', compact('applications', 'clinics', 'dateFrom', 'dateTo'));
    }

}
