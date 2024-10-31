<?php

use App\Http\Controllers\Admin\Web\ApplicationController;
use App\Http\Controllers\Admin\Web\BotUserController;
use App\Http\Controllers\Admin\Web\CategoryController;
use App\Http\Controllers\Admin\Web\CityController;
use App\Http\Controllers\Admin\Web\ClinicController;
use App\Http\Controllers\Admin\Web\CountryController;
use App\Http\Controllers\Admin\Web\CurrencyController;
use App\Http\Controllers\Admin\Web\DashboardController;
use App\Http\Controllers\Admin\Web\DiseaseTypeController;
use App\Http\Controllers\Admin\Web\EntertainmentController;
use App\Http\Controllers\Admin\Web\EstablishmentController;
use App\Http\Controllers\Admin\Web\HotelController;
use App\Http\Controllers\Admin\Web\MailingController;
use App\Http\Controllers\Admin\Web\PromotionController;
use App\Http\Controllers\Admin\Web\SpecializationController;
use App\Http\Controllers\Admin\Web\UsefulInformationController;
use App\Http\Controllers\Admin\Web\UserController;
use App\Http\Controllers\Admin\Web\ViewedStatisticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Telegram\TelegramController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('set-lang/{locale}/{botUser?}', [DashboardController::class, 'setLocale'])->name('set.lang');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'role:admin'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/statistics', [DashboardController::class, 'exportStatistics'])->name(
            'dashboard.statistics.export'
        );
        Route::resource('/users', UserController::class);
        Route::get('bot-users', [BotUserController::class, 'index'])->name('bot.users');
        Route::get('bot-users/statistics', [BotUserController::class, 'exportStatistics'])->name(
            'bot.users.statistics.export'
        );
        Route::get('bot-user-journey/{user}', [BotUserController::class, 'showJourney'])->name('bot.user.journey');
        Route::get('mailing', [MailingController::class, 'index'])->name('mailing');

        Route::get('viewed-statistics', [ViewedStatisticsController::class, 'index'])->name('viewed.statistics');
        Route::get('viewed-statistics/export', [ViewedStatisticsController::class, 'exportStatistics'])->name('viewed.statistics.export');
    });

    Route::get('applications', [ApplicationController::class, 'index'])->name('applications');

    Route::resource('currencies', CurrencyController::class);
    Route::resource('establishments', EstablishmentController::class);
    Route::resource('entertainments', EntertainmentController::class);
    Route::resource('hotels', HotelController::class);
    Route::resource('usefulInfos', UsefulInformationController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('clinics', ClinicController::class);
    Route::resource('diseaseTypes', DiseaseTypeController::class);
    Route::resource('specializations', SpecializationController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('cities', CityController::class);

    Route::post('bot-user/send-message', [TelegramController::class, 'sendMessageToUser'])->name('bot.sendMessage');
});

// Telegram
Route::prefix('telegram')->group(function () {
    Route::get('webhook', function () {
        $telegram = new Api(config('telegram.bot_token'));
        $hook = $telegram->setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($hook);
    });

    Route::post('webhook', [TelegramController::class, 'handleWebhook']);
});
