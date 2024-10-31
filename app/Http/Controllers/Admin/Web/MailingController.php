<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Models\Category;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\Currency;
use App\Models\DiseaseType;
use App\Models\Entertainment;
use App\Models\Establishment;
use App\Models\Hotel;
use App\Models\Promotion;
use App\Models\Specialization;
use App\Models\UsefulInformation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailingController extends Controller
{
    public function index(Request $request): View
    {
        $clinics = Clinic::all();
        $categories = Category::all();
        $cities = City::all();
        $countries = Country::all();
        $currencies = Currency::all();
        $diseaseTypes = DiseaseType::all();
        $entertainments = Entertainment::all();
        $establishments = Establishment::all();
        $hotels = Hotel::all();
        $promotions = Promotion::all();
        $specializations = Specialization::all();
        $usefulInformations = UsefulInformation::all();

        $botUsers = BotUser::query()
            ->when($request->input('clinic_id'), function ($query, $clinicId) {
                $query->whereHas('views', function ($q) use ($clinicId) {
                    $q->where('viewable_id', $clinicId)
                        ->where('viewable_type', Clinic::class);
                });
            })
            ->when($request->input('category_id'), function ($query, $categoryId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $categoryId)->where('viewable_type', Category::class)
                );
            })
            ->when($request->input('city_id'), function ($query, $cityId) {
                $query->where('city_id', $cityId);
            })
            ->when($request->input('country_id'), function ($query, $countryId) {
                $query->where('country_id', $countryId);
            })
            ->when($request->input('currency_id'), function ($query, $currencyId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $currencyId)->where(
                        'viewable_type',
                        Currency::class
                    )
                );
            })
            ->when($request->input('disease_type_id'), function ($query, $diseaseTypeId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $diseaseTypeId)->where('viewable_type', DiseaseType::class)
                );
            })
            ->when($request->input('entertainment_id'), function ($query, $entertainmentId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $entertainmentId)->where('viewable_type', Entertainment::class)
                );
            })
            ->when($request->input('establishment_id'), function ($query, $establishmentId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $establishmentId)->where('viewable_type', Establishment::class)
                );
            })
            ->when($request->input('hotel_id'), function ($query, $hotelId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $hotelId)->where('viewable_type', Hotel::class)
                );
            })
            ->when($request->input('promotion_id'), function ($query, $promotionId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $promotionId)->where('viewable_type', Promotion::class)
                );
            })
            ->when($request->input('specialization_id'), function ($query, $specializationId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $specializationId)->where('viewable_type', Specialization::class)
                );
            })
            ->when($request->input('useful_information_id'), function ($query, $usefulInformationId) {
                $query->whereHas(
                    'views',
                    fn($q) => $q->where('viewable_id', $usefulInformationId)->where(
                        'viewable_type',
                        UsefulInformation::class
                    )
                );
            })
            ->get();

        return view(
            'admin.mailing.index',
            compact(
                'clinics',
                'categories',
                'cities',
                'countries',
                'currencies',
                'diseaseTypes',
                'entertainments',
                'establishments',
                'hotels',
                'promotions',
                'specializations',
                'usefulInformations',
                'botUsers'
            )
        );
    }
}
