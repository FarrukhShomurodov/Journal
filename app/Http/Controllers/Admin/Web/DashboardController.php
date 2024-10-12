<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{

    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {


        return view('admin.dashboard');
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
