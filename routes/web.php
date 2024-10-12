<?php

use App\Http\Controllers\Admin\Web\BotUserController;
use App\Http\Controllers\Admin\Web\CategoryController;
use App\Http\Controllers\Admin\Web\ClinicController;
use App\Http\Controllers\Admin\Web\CurrencyController;
use App\Http\Controllers\Admin\Web\DashboardController;
use App\Http\Controllers\Admin\Web\DiseaseTypeController;
use App\Http\Controllers\Admin\Web\EntertainmentController;
use App\Http\Controllers\Admin\Web\EstablishmentController;
use App\Http\Controllers\Admin\Web\HotelController;
use App\Http\Controllers\Admin\Web\PromotionController;
use App\Http\Controllers\Admin\Web\SpecializationController;
use App\Http\Controllers\Admin\Web\UsefulInformationController;
use App\Http\Controllers\Admin\Web\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Telegram\TelegramController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('set-lang/{locale}/{botUser?}', [DashboardController::class, 'setLocale'])->name('set.lang');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('bot-users', BotUserController::class);

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

Route::get('test', function () {
    $clinic = \App\Models\Clinic::first();
    dd($clinic);
});
