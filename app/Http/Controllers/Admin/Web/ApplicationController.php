<?php

namespace App\Http\Controllers\Admin\Web;

use App\Models\Clinic;
use App\Models\Specialization;
use Illuminate\View\View;

class ApplicationController
{
    public function index(): View
    {
        $clinics = Clinic::query()->orderBy('id', 'asc')->get();
        $specializations = Specialization::query()->get();

        return view(
            'admin.applications.index',
            compact('clinics', 'specializations')
        );
    }

}
