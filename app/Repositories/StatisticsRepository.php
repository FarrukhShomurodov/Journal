<?php

namespace App\Repositories;

use App\Models\BotUser;
use App\Models\BotUserJourney;
use App\Models\BotUserSession;
use App\Models\City;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsRepository
{
    public function dashboardStatistics(): array
    {
        return [
            'dau' => $this->getDAU(),
            'wau' => $this->getWAU(),
            'mau' => $this->getMAU(),
            'average_session_length' => $this->getAverageSessionLength(),
            'drop_off_point' => $this->dropOffPoint() ?? '-',
            'user_counts_by_menu_section' => $this->getUniqueUserCountsByMenuSection(),
            'user_journey_completion_rate' => $this->UserJourneyCompletionRate(),
            'most_frequent_сountry' => $this->mostFrequentCountry(),
            'most_frequent_city' => $this->mostFrequentCity(),
        ];
    }

    public function getDAU(): int
    {
        return BotUser::query()->whereDate('created_at', today())->count();
    }

    public function getWAU(): int
    {
        return BotUser::query()->whereDate('created_at', '>=', now()->subDays(7))->count();
    }

    public function getMAU(): int
    {
        return BotUser::query()->whereDate('created_at', '>=', now()->subDays(30))->count();
    }

    public function calculateRetentionRate($dateTo, $dateFrom)
    {
        $userCount = BotUser::query()->count();
        $usersActive = BotUser::query()->whereBetween('last_activity', [Carbon::parse($dateTo), Carbon::parse($dateFrom)])->count();

        if ($userCount == 0) {
            return 0;
        }

        return (100 * $usersActive) / $userCount;
    }

    public function getAverageSessionLength(): int|null
    {
        return BotUserSession::query()
            ->whereNotNull('session_end')
            ->select(DB::raw('AVG(EXTRACT(EPOCH FROM (session_end - session_start))) as avg_session_length'))
            ->first()
            ->avg_session_length;
    }

    public function getAverageSessionLength(): int|null
    {
        return BotUserSession::query()
            ->whereNotNull('session_end')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, session_start, session_end)) as avg_session_length'))
            ->first()
            ->avg_session_length;
    }


//    public function getAverageSessionFrequency($dateTo, $dateFrom): float|int|null
//    {
//        $sessionCounts = BotUserSession::query()
//            ->whereBetween('session_start', [Carbon::parse($dateTo), Carbon::parse($dateFrom)])
//            ->groupBy('bot_user_id')
//            ->selectRaw('count(id) as session_count')
//            ->pluck('session_count');
//
//        if ($sessionCounts->isEmpty()) {
//            return 0;
//        }
//
//        return $sessionCounts->average();
//    }

    public function calculateChurnRate($dateTo, $dateFrom): float|int
    {
        $userCount = BotUser::query()->count();
        $usersActive = BotUser::query()->whereBetween('last_activity', [Carbon::parse($dateTo), Carbon::parse($dateFrom)])->count();

        $unUsedUsers = $userCount - $usersActive;

        if ($unUsedUsers == 0) {
            return 0;
        }

        return (100 * $unUsedUsers) / $userCount;
    }

    public function dropOffPoint(): string|null
    {
        return BotUserJourney::query()->select('event_name')
            ->where('event_name', '!=', 'Главное меню')
            ->groupBy('event_name')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->pluck('event_name')
            ->first();
    }

    public function getUniqueUserCountsByMenuSection()
    {
        $botUsers = BotUser::query()->get();
        $count = 0;
        $sections = [
            'Поиск клиники',
            'Поиск лечения',
            'Каталог клиник',
            'Топ клиники',
            'Акции',
            'Полезная информация',
            'Отели',
            'Отдых/развлечения',
            'Где поесть?',
            'Калькулятор валют',
            'Настройки',
        ];

        foreach ($botUsers as $botUser) {
            $hasJourney = $botUser->journey()->whereIn('event_name', $sections)->exists();
            if ($hasJourney) {
                $count++;
            }
        }

        return $count;
    }

    public function UserJourneyCompletionRate(): array
    {
        $botUsers = BotUser::query()->get();
        $botUsersCount = $botUsers->count();
        $applicationsCount = 0;
        $promotionViewCount = 0;
        $useFullInformationViewCount = 0;
        $hotelViewCount = 0;
        $entertainmentViewCount = 0;
        $establishmentViewCount = 0;
        $currencyViewCount = 0;

        foreach ($botUsers as $botUser) {
            $application = $botUser->application()->exists();
            $promotionView = $botUser->journey()->where('event_name', 'LIKE', '%Просмотр акции%')->exists();
            $useFullInformationView = $botUser->journey()->where('event_name', 'LIKE', '%Просмотр полезной ифнормации%')->exists();
            $hotelView = $botUser->journey()->where('event_name', 'LIKE', '%Просмотр отеля%')->exists();
            $entertainmentView = $botUser->journey()->where('event_name', 'LIKE', '%Просмотр развлечения%')->exists();
            $establishmentView = $botUser->journey()->where('event_name', 'LIKE', '%Просмотр информации об заведении%')->exists();
            $currencyView = $botUser->journey()->where('event_name', 'LIKE', '%ц%')->exists();

            if ($application) {
                $applicationsCount++;
            }
            if ($promotionView) {
                $promotionViewCount++;
            }
            if ($useFullInformationView) {
                $useFullInformationViewCount++;
            }
            if ($hotelView) {
                $hotelViewCount++;
            }
            if ($establishmentView) {
                $establishmentViewCount++;
            }
            if ($currencyView) {
                $currencyViewCount++;
            }
            if ($entertainmentView) {
                $entertainmentViewCount++;
            }
        }

        $userJourneyCompletion['application'] = ($botUsersCount > 0) ? ($applicationsCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['promotionViewCount'] = ($botUsersCount > 0) ? ($promotionViewCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['useFullInformationViewCount'] = ($botUsersCount > 0) ? ($useFullInformationViewCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['hotelViewCount'] = ($botUsersCount > 0) ? ($hotelViewCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['establishmentViewCount'] = ($botUsersCount > 0) ? ($establishmentViewCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['currencyViewCount'] = ($botUsersCount > 0) ? ($currencyViewCount / $botUsersCount) * 100 : 0;
        $userJourneyCompletion['entertainmentViewCount'] = ($botUsersCount > 0) ? ($entertainmentViewCount / $botUsersCount) * 100 : 0;

        return $userJourneyCompletion;
    }

    public function activeUsers(): Collection|array
    {
        return BotUser::query()->where('last_activity', '<=', now())
            ->selectRaw('DATE(last_activity) as date, COUNT(*) as count')
            ->groupBy('last_activity')
            ->get();
    }

    public function mostFrequentCountry(): string
    {
        $countryId = BotUser::query()->select('country_id', DB::raw('COUNT(*) as user_count'))
            ->groupBy('country_id')
            ->orderBy('user_count', 'desc')
            ->first();

        $country = null;
        if ($countryId) {
            $country = Country::query()->find($countryId->country_id);
        }

        if ($country) {
            return $country->name['ru'];
        } else {
            return "Нет данных о пользователях.";
        }
    }

    public function mostFrequentCity(): string
    {
        $cityId = BotUser::query()->select('city_id', DB::raw('COUNT(*) as user_count'))
            ->groupBy('city_id')
            ->orderBy('user_count', 'desc')
            ->first();

        $city = null;
        if ($cityId) {
            $city = City::query()->find($cityId->city_id);
        }

        if ($city) {
            return $city->name['ru'];
        } else {
            return "Нет данных о пользователях.";
        }
    }
}
