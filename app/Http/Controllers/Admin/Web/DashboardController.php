<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Repositories\StatisticsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{

    protected StatisticsRepository $statisticsRepository;

    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }


    public function index(Request $request): View
    {

        $dateFrom = $request->input('date_from', now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $dateFromFrequency = $request->input('date_from_session_frequency', now()->subDays(7)->format('Y-m-d'));
        $dateToFrequency = $request->input('date_to_session_frequency', now()->format('Y-m-d'));

        $dateFromChurn = $request->input('date_from_session_churn', now()->subDays(7)->format('Y-m-d'));
        $dateToChurn = $request->input('date_to_session_churn', now()->format('Y-m-d'));

        $statistics['active_users'] = $this->statisticsRepository->dashboardStatistics();
        $statistics['retention_rate'] = $this->statisticsRepository->calculateRetentionRate($dateFrom, $dateTo);
        $statistics['get_average_session_frequency'] = $this->statisticsRepository->getAverageSessionFrequency($dateFromFrequency, $dateToFrequency);
        $statistics['calculate_churn_rate'] = $this->statisticsRepository->calculateChurnRate($dateFromChurn, $dateToChurn);

        $chartLabels = $this->statisticsRepository->activeUsers()->pluck('date');
        $chartData = $this->statisticsRepository->activeUsers()->pluck('count');

        return view('admin.dashboard', compact('statistics', 'dateFrom', 'dateTo', 'dateFromFrequency', 'dateToFrequency', 'dateFromChurn', 'dateToChurn', 'chartLabels', 'chartData'));
    }

    public function setLocale($locale, $botUser = null): RedirectResponse
    {
        if (!in_array($locale, ['ru', 'uz'])) {
            abort(400, 'Unsupported locale');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        if ($botUser) {
            $user = BotUser::find($botUser);

            if ($user) {
                $user->update(['lang' => $locale]);
            } else {
                abort(404, 'Bot user not found');
            }
        }

        return redirect()->back();
    }
}
