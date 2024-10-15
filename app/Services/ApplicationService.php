<?php

namespace App\Services;

use App\Models\Application;

class ApplicationService
{
    public function isReviewed(Application $application, array $validated): void
    {
        $application->update(['is_reviewed' => $validated['is_reviewed']]);
    }
}
