<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BotUserController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        $query = BotUser::query()->orderBy('id', 'asc');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $botUsers = $query->get();
        return view('admin.users.bot-users', compact('botUsers', 'dateFrom', 'dateTo'));
    }

    public function showJourney(BotUser $user): View
    {
        $journeys = $user->journey;
        return view('admin.users.journey', compact('journeys'));
    }
}
