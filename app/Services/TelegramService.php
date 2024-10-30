<?php

namespace App\Services;

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
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramService
{
    protected Api $telegram;
    protected $user;
    protected string $lang;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    public function processMessage($chatId, $text, $step, $message, $user): void
    {
        $this->user = $user;
        $this->lang = $user->lang ?? 'en';

        $commands = [
            // Clinic
            '🔍 ' . __('telegram.menu.clinic_search') => 'selectSpecialization',
            '💊 ' . __('telegram.menu.treatment_search') => 'selectDiseaseType',
            '📚 ' . __('telegram.menu.clinic_catalog') => 'clinicList',
            '🌟 ' . __('telegram.menu.top_clinics') => 'clinicTop',
            __('telegram.menu.by_specialization') => function () use ($chatId) {
                $this->selectSpecialization($chatId, true);
            },
            __('telegram.menu.by_disease_type') => function () use ($chatId) {
                $this->selectDiseaseType($chatId, true);
            },
            __('telegram.menu.submit_application') => 'getApplication',

            // Promotion
            '🎉 ' . __('telegram.menu.promotions') => 'selectPromotion',

            // Useful Information
            'ℹ️ ' . __('telegram.menu.useful_info') => 'selectUsefulInfo',

            // Hotel
            '🏨 ' . __('telegram.menu.hotels') => 'selectHotel',

            // Entertainment
            '🎡 ' . __('telegram.menu.entertainment') => 'selectEntertainment',

            // Establishment
            '🍽️ ' . __('telegram.menu.where_to_eat') => 'selectEstablishmentCategory',

            // Currency
            '💱 ' . __('telegram.menu.currency_calculator') => 'selectCurrency',

            // Setting
            '⚙️ ' . __('telegram.menu.settings') => 'settingInformation',

            __('telegram.navigation.home') => 'showMainMenu',
        ];

        if (array_key_exists($text, $commands)) {
            if (is_callable($commands[$text])) {
                $commands[$text]();
            } else {
                $this->{$commands[$text]}($chatId);
            }
        }

        switch ($step) {
            // Lang
            case 'choose_language':
                $this->processLanguageChoice($chatId, $text);
                break;
            case 'edit_language':
                $this->processLanguageChoice($chatId, $text, true);
                break;
            // Location
            case 'select_country':
                $this->processSelectCity($chatId, $text);
                break;
            case 'select_city':
                $city = City::query()->where("name->{$this->lang}", $text)->first();

                if (!$city) {
                    $this->updateUserStep($chatId, 'select_country');

                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => __('telegram.errors.generic_error'),
                    ]);

                    return;
                }

                $this->user->update([
                    'city_id' => $city->id
                ]);

                $this->showMainMenu($chatId);
                break;

            // Clinic
            case 'show_specializations':
                if ($text == __('telegram.navigation.back')) {
                    $this->clinicTop($chatId);
                    break;
                }
                if ($text !== __('telegram.navigation.home')) {
                    $this->clinicList($chatId, $text, 'specialization');
                }
                break;
            case 'show_top_specializations':
                if ($text == __('telegram.navigation.back')) {
                    $this->clinicTop($chatId);
                    break;
                }
                if ($text !== __('telegram.navigation.home')) {
                    $this->clinicList($chatId, $text, 'specialization', true);
                }
                break;
            case 'show_disease_types':
                if ($text == __('telegram.navigation.back')) {
                    $this->clinicTop($chatId);
                    break;
                }

                if ($text !== __('telegram.navigation.home')) {
                    $this->clinicList($chatId, $text, 'disease_type');
                }

                break;
            case 'show_top_disease_types':
                if ($text == __('telegram.navigation.back')) {
                    $this->clinicTop($chatId);
                    break;
                }

                if ($text !== __('telegram.navigation.home')) {
                    $this->clinicList($chatId, $text, 'disease_type', true);
                }

                break;
            case 'show_clinic':
                if ($text == __('telegram.navigation.back')) {
                    $stepInfo = $this->user->previousChoice;

                    if ($stepInfo && $stepInfo->previous_specialization_id) {
                        $this->back($chatId, 'show_specializations');
                    } elseif ($stepInfo && $stepInfo->previous_disease_type_id) {
                        $this->back($chatId, 'show_disease_types');
                    } else {
                        $this->showMainMenu($chatId);
                    }
                } else {
                    $this->showClinicInformation($chatId, $text);
                }
                break;
            case 'show_top_clinic':
                if ($text == __('telegram.navigation.back')) {
                    $stepInfo = $this->user->previousChoice;

                    if ($stepInfo && $stepInfo->previous_specialization_id) {
                        $this->back($chatId, 'show_specializations_top_clinic');
                    } elseif ($stepInfo && $stepInfo->previous_disease_type_id) {
                        $this->back($chatId, 'show_disease_types_top_clinic');
                    } else {
                        $this->showMainMenu($chatId);
                    }
                } else {
                    $this->showClinicInformation($chatId, $text, true);
                }
                break;
            case 'show_clinic_information':
                if ($text == __('telegram.navigation.back')) {
                    $this->back($chatId, 'show_clinic');
                }
                break;
            case 'show_top_clinic_information':
                if ($text == __('telegram.navigation.back')) {
                    $this->back($chatId, 'show_top_clinic');
                }
                break;
            case 'get_application':
                $this->getApplication($chatId);
                break;
            case 'store_application':
                if ($text == __('telegram.navigation.back')) {
                    $userJourney = $this->user->journey()
                        ->whereIn('event_name', ['Выбор клиники', 'Выбор топ клиники'])
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($userJourney) {
                        if ($userJourney->event_name === 'Выбор клиники') {
                            $this->back($chatId, 'show_clinic');
                        } elseif ($userJourney->event_name === 'Выбор топ клиники') {
                            $this->back($chatId, 'show_top_clinic');
                        }
                    } else {
                        $this->showMainMenu($chatId);
                    }
                } else {
                    $this->storeApplication($chatId, $text);
                }
                break;

            // Promotions
            case 'show_promotions':
                if ($text !== __('telegram.navigation.home')) {
                    $this->showPromotionInformation($chatId, $text);
                }
                break;

            // Useful Information
            case 'show_usefulInformation':
                if ($text !== __('telegram.navigation.home')) {
                    $this->showUsefulInfoInformation($chatId, $text);
                }
                break;

            // Hotel
            case 'show_hotel':
                if ($text !== __('telegram.navigation.home')) {
                    $this->showHotelInformation($chatId, $text);
                }
                break;

            // Entertainment
            case 'show_entertainment':
                if ($text !== __('telegram.navigation.home')) {
                    $this->showEntertainmentInformation($chatId, $text);
                }
                break;

            // Establishment
            case 'show_establishment_category':
                if ($text !== __('telegram.navigation.home')) {
                    $this->establishmentList($chatId, $text);
                }
                break;
            case 'show_establishment':
                if ($text === __('telegram.navigation.back')) {
                    $this->back($chatId, 'show_establishment_category');
                } else {
                    $this->showEstablishmentInformation($chatId, $text);
                }
                break;

            // Currency
            case 'show_currency':
                if ($text !== __('telegram.navigation.home')) {
                    $this->showCurrencyInformation($chatId, $text);
                }
                break;

            // Setting
            case 'phone_changed':
                $this->settingInformation($chatId);
                break;

            case 'settings':
                if ($text === __('telegram.settings.language')) {
                    $this->user->update(['step' => 'edit_language']);

                    $keyboard = [
                        ["🇷🇺 Русский", "🇬🇧 English"],
                        ["🇺🇿 O'zbekcha", "🇰🇿 Қазақша"],
                        ["🇹🇯 Тоҷикӣ"]
                    ];

                    $reply_markup = Keyboard::make([
                        'keyboard' => $keyboard,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => false
                    ]);

                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "🇷🇺Пожалуйста, выберите язык.\n\n🇺🇿Iltimos, tilni tanlang.\n\n🇬🇧Please choose a language.\n\n🇰🇿Тілді таңдаңыз.\n\n🇹🇯Лутфан забонро интихоб кунед.",
                        'reply_markup' => $reply_markup
                    ]);
                } elseif ($text === __('telegram.settings.phone_number')) {
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => __('telegram.settings.enter_phone'),
                        'reply_markup' => $this->requestPhoneKeyboard(),
                    ]);
                    $this->updateUserStep($chatId, 'change_phone');
                }
                break;
            // Main menu
//            case 'show_main_menu':
//                $this->showMainMenu($chatId);
//                break;
        }
    }

    private function processLanguageChoice($chatId, $text, $isEdit = false): void
    {
        $lang = [
            "🇷🇺 Русский" => 'ru',
            "🇬🇧 English" => 'en',
            "🇺🇿 O'zbekcha" => 'uz',
            "🇰🇿 Қазақша" => 'kz',
            "🇹🇯 Тоҷикӣ" => 'tj'
        ];

        if (array_key_exists($text, $lang)) {
            $this->updateUserLang($lang[$text]);

            $this->storeUserJourney('Выбор языка');
            $isEdit ? $this->settingInformation($chatId) : $this->processSelectCountry($chatId);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.generic_error')
            ]);
        }
    }

    private function processSelectCountry($chatId): void
    {
        $countries = Country::query()->get();

        if ($countries->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.empty_data'),
            ]);

            return;
        }

        $keyboard = [];

        $toTwoKeyboard = [];

        $count = 0;

        foreach ($countries as $country) {
            $toTwoKeyboard[] = $country->name[$this->lang];

            $count++;
            if ($count === 2) {
                $keyboard[] = $toTwoKeyboard;
                $toTwoKeyboard = [];
                $count = 0;
            }
        }

        if (!empty($toTwoKeyboard)) {
            $keyboard[] = $toTwoKeyboard;
        }

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_country'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'select_country');
        $this->storeUserJourney("Выбор Страны");
    }

    private function processSelectCity($chatId, $text): void
    {
        $country = Country::query()->where("name->{$this->lang}", $text)->first();

        if (!$country) {
            $this->updateUserStep($chatId, 'choose_language');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.generic_error'),
            ]);

            return;
        }

        $this->user->update([
            'country_id' => $country->id
        ]);

        $keyboard = [];

        $toTwoKeyboard = [];

        $count = 0;

        foreach ($country->city as $city) {
            $toTwoKeyboard[] = $city->name[$this->lang];

            $count++;
            if ($count === 2) {
                $keyboard[] = $toTwoKeyboard;
                $toTwoKeyboard = [];
                $count = 0;
            }
        }

        if (!empty($toTwoKeyboard)) {
            $keyboard[] = $toTwoKeyboard;
        }

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_city'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'select_city');
        $this->storeUserJourney("Выбор города");
    }

    private function showMainMenu($chatId): void
    {
        $keyboard = [
            [
                '🔍 ' . __('telegram.menu.clinic_search'),
                '💊 ' . __('telegram.menu.treatment_search'),
            ],
            [
                '📚 ' . __('telegram.menu.clinic_catalog'),
                '🌟 ' . __('telegram.menu.top_clinics'),
            ],
            [
                '🎉 ' . __('telegram.menu.promotions'),
                'ℹ️ ' . __('telegram.menu.useful_info'),
            ],
            [
                '🏨 ' . __('telegram.menu.hotels'),
                '🎡 ' . __('telegram.menu.entertainment'),
            ],
            [
                '🍽️ ' . __('telegram.menu.where_to_eat'),
                '💱 ' . __('telegram.menu.currency_calculator'),
            ],
            [
                '⚙️ ' . __('telegram.menu.settings'),
            ],
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false

        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.menu.main_menu'),
            'reply_markup' => $reply_markup
        ]);


        $this->user->previousChoice()->updateOrCreate(
            ['bot_user_id' => $this->user->id],
            [
                'previous_specialization_id' => null,
                'previous_disease_type_id' => null,
                'previous_clinic_id ' => null
            ]
        );

        $this->updateUserStep($chatId, 'show_main_menu');
        $this->storeUserJourney('Главное меню');
    }

    // Clinic
    private function selectSpecialization(
        $chatId,
        $isTop = false
    ): void {
        $specializations = Specialization::query()->get();

        if ($specializations->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.empty_data'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($specializations as $specialization) {
            $keyboard[] = [$specialization->name[$this->lang]];
        }

        if ($this->user->step == 'clinic_top' || $this->user->step == 'show_top_clinic') {
            $keyboard[] = [
                __('telegram.navigation.back')
            ];
        } else {
            $keyboard[] = [
                __('telegram.navigation.home')
            ];
        }


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_specialization'),
            'reply_markup' => $reply_markup
        ]);

        $step = $isTop ? 'show_top_specializations' : 'show_specializations';
        $this->updateUserStep($chatId, $step);
        $this->storeUserJourney('Выбор специализации');
    }

    private
    function selectDiseaseType(
        $chatId,
        $isTop = false
    ): void {
        $diseaseTypes = DiseaseType::query()->get();

        if ($diseaseTypes->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.empty_data'),
            ]);
            return;
        }

        $keyboard = [];

        foreach ($diseaseTypes as $diseaseType) {
            $keyboard[] = [$diseaseType->name[$this->lang]];
        }

        if ($this->user->step == 'clinic_top' || $this->user->step == 'show_top_clinic') {
            $keyboard[] = [
                __('telegram.navigation.back')
            ];
        } else {
            $keyboard[] = [
                __('telegram.navigation.home')
            ];
        }

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_disease_type'),
            'reply_markup' => $reply_markup
        ]);

        $step = $isTop ? 'show_top_disease_types' : 'show_disease_types';
        $this->updateUserStep($chatId, $step);
        $this->storeUserJourney('Выбор тип болезни');
    }

    private
    function clinicTop(
        $chatId
    ): void {
        $keyboard[] = [
            __('telegram.menu.by_disease_type'),
            __('telegram.menu.by_specialization')
        ];

        $keyboard[] = [
            __('telegram.navigation.home')
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.top_clinics'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'clinic_top');
        $this->storeUserJourney('Выбор топ клиники');
    }

    private
    function clinicList(
        $chatId,
        $text = null,
        $from = null,
        $isTop = false
    ): void {
        if ($from == 'specialization') {
            if (is_integer($text)) {
                $specialization = Specialization::query()->find($text);
            } else {
                $specialization = Specialization::query()->whereJsonContains("name->{$this->lang}", $text)->first();
            }

            if (!$specialization) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => __('telegram.errors.specialization_not_found'),
                ]);
                return;
            }

            $this->user->previousChoice()->updateOrCreate(
                ['bot_user_id' => $this->user->id],
                [
                    'previous_specialization_id' => $specialization->id,
                    'previous_disease_type_id' => null
                ]
            );

            $clinics = $isTop ? $specialization->clinics()->orderByRating()->get() : $specialization->clinics;
        } elseif ($from == 'disease_type') {
            if (is_integer($text)) {
                $diseaseType = DiseaseType::query()->find($text);
            } else {
                $diseaseType = DiseaseType::query()->whereJsonContains("name->{$this->lang}", $text)->first();
            }

            if (!$diseaseType) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => __('telegram.errors.disease_type_not_found'),
                ]);
                return;
            }

            $this->user->previousChoice()->updateOrCreate(
                ['bot_user_id' => $this->user->id],
                [
                    'previous_disease_type_id' => $diseaseType->id,
                    'previous_specialization_id' => null
                ]
            );

            $clinics = $isTop ? $diseaseType->clinics()->orderByRating()->get() : $diseaseType->clinics;
        } else {
            $clinics = Clinic::query()->get();
        }

        if ($clinics->isEmpty()) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.specialization_not_found'),
            ]);
        } else {
            $keyboard = [];
            $toTwoKeyboard = [];

            $count = 0;

            foreach ($clinics as $clinic) {
                $toTwoKeyboard[] = $clinic->name[$this->lang];

                $count++;
                if ($count === 2) {
                    $keyboard[] = $toTwoKeyboard;
                    $toTwoKeyboard = [];
                    $count = 0;
                }
            }

            if (!empty($toTwoKeyboard)) {
                $keyboard[] = $toTwoKeyboard;
            }

            $keyboard[] = [
                __('telegram.navigation.back')
            ];

            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.messages.clinic_list'),
                'reply_markup' => $reply_markup
            ]);

            $step = $isTop ? 'show_top_clinic' : 'show_clinic';
            $this->updateUserStep($chatId, $step);

            $event = $isTop ? 'Выбор топ клиники' : 'Выбор клиники';
            $this->storeUserJourney($event);
        }
    }

    private
    function showClinicInformation(
        $chatId,
        $text,
        $isTop = false
    ): void {
        $clinic = Clinic::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$clinic) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.clinic_info_not_found'),
            ]);
            return;
        }
        $photos = $clinic->images;


        $clinicDescription = $clinic->description ? "*📝 Описание:* _{$clinic->description[$this->lang]}_\n" : '';

        $contacts = '';
        foreach ($clinic->contacts['type'] as $index => $contactType) {
            if ($contactType) {
                $contacts .= "• *{$contactType[$this->lang]}:* {$clinic->contacts['type_value'][$index]}\n";
            }
        }

        $contactList = strlen($contacts) > 1 ? "📞 *" . __(
                'telegram.fields.contacts'
            ) . "*\n" . $contacts : $contacts;

        $description = "*{$clinic->name[$this->lang]}*\n\n"
            . "⭐ *" . __('telegram.fields.rating') . "* _{$clinic->rating}_\n"
            . "📅 *" . __('telegram.fields.working_hours') . "* _{$clinic->working_hours}_\n"
            . $clinicDescription
            . "📍 *" . __('telegram.fields.location') . "* [" . __(
                'telegram.fields.link'
            ) . "]($clinic->location_link)\n\n"
            . $contactList;


        $keyboard[] = [
            __('telegram.menu.submit_application'),
            __('telegram.navigation.back')
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'reply_markup' => $reply_markup,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.messages.submit_application'),
                'reply_markup' => $reply_markup,
                'parse_mode' => 'Markdown'
            ]);
        }

        $this->user->previousChoice()->updateOrCreate(
            ['bot_user_id' => $this->user->id],
            ['previous_clinic_id' => $clinic->id]
        );

        $step = $isTop ? 'show_top_clinic_information' : 'show_clinic_information';
        $this->updateUserStep($chatId, $step);

        $this->storeUserJourney("Просмотр информации о клинике " . $clinic->name['ru']);
    }

    // Application
    private
    function getApplication(
        $chatId
    ): void {
        if ($this->user->phone) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Создайте заявку.",
            ]);
            $this->updateUserStep($chatId, 'store_application');
            $this->storeUserJourney("Напишите заявку");
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Введите номер телефона.",
                'reply_markup' => $this->requestPhoneKeyboard(),
            ]);
            $this->updateUserStep($chatId, 'get_application');
            $this->storeUserJourney(__('telegram.settings.enter_phone'));
        }
    }

    private
    function storeApplication(
        $chatId,
        $text
    ): void {
        $clinicId = $this->user->previousChoice->previous_clinic_id;

        try {
            $this->user->application()->create([
                'clinic_id' => $clinicId,
                'text' => $text,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.messages.application_submitted')
            ]);
            $this->storeUserJourney("Заявка сохранена");
        } catch (Exception $e) {
            Log::error('Application storage failed: ' . $e->getMessage());

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.generic_error')
            ]);
        }

        $this->showMainMenu($chatId);
    }

    // Promotion
    private
    function selectPromotion(
        $chatId
    ): void {
        $promotions = Promotion::query()->get();

        if ($promotions->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.promotions_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($promotions as $promotion) {
            $keyboard[] = [$promotion->name[$this->lang]];
        }

        $keyboard[] = [
            __('telegram.navigation.home')
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_promotion'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_promotions');
        $this->storeUserJourney("Выбор акции");
    }

    private
    function showPromotionInformation(
        $chatId,
        $text
    ): void {
        $promotion = Promotion::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$promotion) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.promotion_info_not_found'),
            ]);
            return;
        }
        $photos = $promotion->images;


        $promotionDescription = $promotion->description ? "*📝 Описание:* _{$promotion->description[$this->lang]}_\n" : '';

        $description = "*{$promotion->name[$this->lang]}*\n\n"
            . $promotionDescription;


        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }

        $this->storeUserJourney("Просмотр акции " . $promotion->name[$this->lang]);
    }

    // UsefulInfo
    private
    function selectUsefulInfo(
        $chatId
    ): void {
        $usefulInformations = UsefulInformation::query()->get();

        if ($usefulInformations->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.useful_info_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($usefulInformations as $usefulInformation) {
            $keyboard[] = [$usefulInformation->name[$this->lang]];
        }

        $keyboard[] = [
            __('telegram.navigation.home')
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_article'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_usefulInformation');
        $this->storeUserJourney("Выбор полезной информации");
    }

    private
    function showUsefulInfoInformation(
        $chatId,
        $text
    ): void {
        $usefulInformation = UsefulInformation::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$usefulInformation) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.faq_not_found'),
            ]);
            return;
        }
        $photos = $usefulInformation->images;


        $promotionDescription = $usefulInformation->description ? "*📝 Описание:* _{$usefulInformation->description[$this->lang]}_\n" : '';

        $description = "*{$usefulInformation->name[$this->lang]}*\n\n"
            . $promotionDescription;


        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }

        $this->storeUserJourney("Просмотр полезной ифнормации " . $usefulInformation->name['ru']);
    }

    // Hotel
    private
    function selectHotel(
        $chatId
    ): void {
        $hotels = Hotel::query()->get();

        if ($hotels->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.hotels_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($hotels as $hotel) {
            $keyboard[] = [$hotel->name[$this->lang]];
        }

        $keyboard[] = [
            __('telegram.navigation.home')
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_hotel'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_hotel');
        $this->storeUserJourney("Выбор отеля");
    }

    private
    function showHotelInformation(
        $chatId,
        $text
    ): void {
        $hotel = Hotel::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$hotel) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.hotels_not_found'),
            ]);
            return;
        }
        $photos = $hotel->images;


        $clinicDescription = $hotel->description ? "*📝 Описание:* _{$hotel->description[$this->lang]}_\n" : '';

        $contacts = '';
        foreach ($hotel->contacts['type'] as $index => $contactType) {
            if ($contactType) {
                $contacts .= "• *{$contactType[$this->lang]}:* {$hotel->contacts['type_value'][$index]}\n";
            }
        }

        $contactList = strlen($contacts) > 1 ? "📞 *" . __(
                'telegram.fields.contacts'
            ) . "*\n" . $contacts : $contacts;

        $priceFrom = round($hotel->price_from);
        $priceTo = round($hotel->price_to);

        if ($hotel->price_from && $hotel->price_to) {
            $priceRange = "$priceFrom - $priceTo";
        } elseif ($hotel->price_from) {
            $priceRange = __('telegram.fields.from') . ' ' . $priceFrom;
        } elseif ($hotel->price_to) {
            $priceRange = __('telegram.fields.to') . ' ' . $priceTo;
        } else {
            $priceRange = '-';
        }

        $description = "*{$hotel->name[$this->lang]}*\n\n"
            . "📅 *" . __('telegram.fields.working_hours') . "* _{$hotel->working_hours}_\n"
            . $clinicDescription
            . "📍 *" . __('telegram.fields.location') . "* [" . __(
                'telegram.fields.link'
            ) . "]($hotel->location_link)\n\n"
            . "💰 *" . __('telegram.fields.price_range') . ":" . "* _{$priceRange}_\n\n"
            . $contactList;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
        $this->storeUserJourney("Просмотр отеля " . $hotel->name['ru']);
    }

    // Entertainment
    private
    function selectEntertainment(
        $chatId
    ): void {
        $entertainments = Entertainment::query()->get();

        if ($entertainments->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.entertainment_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($entertainments as $entertainment) {
            $keyboard[] = [$entertainment->name[$this->lang]];
        }

        $keyboard[] = [
            __('telegram.navigation.home')
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_entertainment'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_entertainment');
        $this->storeUserJourney("Выбор развлечения");
    }

    private
    function showEntertainmentInformation(
        $chatId,
        $text
    ): void {
        $entertainment = Entertainment::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$entertainment) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.entertainment_not_found'),
            ]);
            return;
        }
        $photos = $entertainment->images;

        $entertainmentDescription = $entertainment->description ? "*📝 Описание:* _{$entertainment->description[$this->lang]}_\n" : '';

        $contacts = '';
        foreach ($entertainment->contacts['type'] as $index => $contactType) {
            if ($contactType) {
                $contacts .= "• *{$contactType[$this->lang]}:* {$entertainment->contacts['type_value'][$index]}\n";
            }
        }
        $contactList = strlen($contacts) > 1 ? "📞 *" . __(
                'telegram.fields.contacts'
            ) . "*\n" . $contacts : $contacts;

        $priceFrom = round($entertainment->price_from);
        $priceTo = round($entertainment->price_to);

        if ($entertainment->price_from && $entertainment->price_to) {
            $priceRange = "$priceFrom - $priceTo";
        } elseif ($entertainment->price_from) {
            $priceRange = __('telegram.fields.from') . ' ' . $priceFrom;
        } elseif ($entertainment->price_to) {
            $priceRange = __('telegram.fields.to') . ' ' . $priceTo;
        } else {
            $priceRange = '-';
        }

        $description = "*{$entertainment->name[$this->lang]}*\n\n"
            . "📅 *" . __('telegram.fields.working_hours') . "* _{$entertainment->working_hours}_\n"
            . $entertainmentDescription
            . "📍 *" . __('telegram.fields.location') . "* [" . __(
                'telegram.fields.link'
            ) . "]($entertainment->location_link)\n\n"
            . "💰 *" . __('telegram.fields.price_range') . ":" . "* _{$priceRange}_\n\n"
            . $contactList;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
        $this->storeUserJourney("Просмотр развлечения " . $entertainment->name['ru']);
    }

    // Establishment
    private
    function selectEstablishmentCategory(
        $chatId
    ): void {
        $categories = Category::query()->get();

        if ($categories->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.establishments_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        foreach ($categories as $category) {
            $keyboard[] = [$category->name[$this->lang]];
        }

        $keyboard[] = [
            __('telegram.navigation.home')
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_category'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_establishment_category');
        $this->storeUserJourney("Выбор категорию заведения");
    }

    private
    function establishmentList(
        $chatId,
        $text
    ): void {
        $category = Category::query()->whereJsonContains("name->{$this->lang}", $text)->first();
        $establishments = $category->establishments;

        if ($establishments->isEmpty()) {
            $this->selectEstablishmentCategory($chatId);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.establishments_not_found'),
            ]);
        } else {
            $keyboard = [];

            foreach ($establishments as $establishment) {
                $keyboard[] = [$establishment->name[$this->lang]];
            }

            $keyboard[] = [
                __('telegram.navigation.back')
            ];

            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.messages.establishment_list'),
                'reply_markup' => $reply_markup
            ]);

            $this->updateUserStep($chatId, 'show_establishment');
        }

        $this->storeUserJourney("Выбор заведения");
    }

    private
    function showEstablishmentInformation(
        $chatId,
        $text
    ): void {
        $establishment = Establishment::query()->whereJsonContains("name->{$this->lang}", $text)->first();

        if (!$establishment) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.establishments_not_found'),
            ]);
            return;
        }
        $photos = $establishment->images;

        $establishmentDescription = $establishment->description ? "*📝 Описание:* _{$establishment->description[$this->lang]}_\n" : '';

        $contacts = '';
        foreach ($establishment->contacts['type'] as $index => $contactType) {
            if ($contactType) {
                $contacts .= "• *{$contactType[$this->lang]}:* {$establishment->contacts['type_value'][$index]}\n";
            }
        }

        $contactList = strlen($contacts) > 1 ? "📞 *" . __(
                'telegram.fields.contacts'
            ) . "*\n" . $contacts : $contacts;

        $priceFrom = round($establishment->price_from);
        $priceTo = round($establishment->price_to);

        if ($establishment->price_from && $establishment->price_to) {
            $priceRange = "$priceFrom - $priceTo";
        } elseif ($establishment->price_from) {
            $priceRange = __('telegram.fields.from') . ' ' . $priceFrom;
        } elseif ($establishment->price_to) {
            $priceRange = __('telegram.fields.to') . ' ' . $priceTo;
        } else {
            $priceRange = '-';
        }

        $description = "*{$establishment->name[$this->lang]}*\n\n"
            . "📅 *" . __('telegram.fields.working_hours') . "* _{$establishment->working_hours}_\n"
            . $establishmentDescription
            . "📍 *" . __('telegram.fields.location') . "* [" . __(
                'telegram.fields.link'
            ) . "]($establishment->location_link)\n\n"
            . "💰 *" . __('telegram.fields.price_range') . ":" . "* _{$priceRange}_\n\n"
            . $contactList;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];

            foreach ($photos as $index => $photo) {
                $photoPath = Storage::url('public/' . $photo->url);
                $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => $fullPhotoUrl,
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }

        $this->storeUserJourney("Просмотр информации об заведение " . $establishment->name['ru']);
    }

    // Currency
    private
    function selectCurrency(
        $chatId
    ): void {
        $currencies = Currency::query()->get();

        if ($currencies->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.currency_not_found'),
            ]);

            return;
        }

        $keyboard = [];

        $keyboard[] = [
            __('telegram.navigation.home')
        ];

        $toThreeKeyboard = [];
        $count = 0;

        foreach ($currencies as $currency) {
            $toThreeKeyboard[] = $currency->ccy;

            $count++;
            if ($count === 3) {
                $keyboard[] = $toThreeKeyboard;
                $toThreeKeyboard = [];
                $count = 0;
            }
        }

        if (!empty($toThreeKeyboard)) {
            $keyboard[] = $toThreeKeyboard;
        }

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.messages.select_currency'),
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_currency');
        $this->storeUserJourney("Выбор валюты");
    }

    private
    function showCurrencyInformation(
        $chatId,
        $text
    ): void {
        $currency = Currency::query()->where('ccy', $text)->first();

        if (!$currency) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.errors.currency_not_found'),
            ]);

            return;
        }

        $information = "💱 *{$currency->ccy}*\n\n"
            . "💵 *" . __('telegram.fields.currency') . ":* _{$currency->rate}_\n"
            . "📅 *" . __('telegram.fields.date') . ":* _{$currency->relevance_date}_";

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $information,
            'parse_mode' => 'Markdown'
        ]);

        $this->storeUserJourney("Просмотр валюты " . $currency->ccy);
    }

    // Setting
    private
    function settingInformation(
        $chatId
    ): void {
        $keyboard[] = [
            __('telegram.settings.language'),
            __('telegram.settings.phone_number'),
        ];

        $keyboard[] = [
            __('telegram.navigation.home'),
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $lang = [
            'ru' => "Русский",
            'en' => "English",
            'uz' => "O'zbekcha",
            'kz' => "Қазақша",
            'tj' => "Тоҷикӣ"
        ];

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => '*' . '⚙️ ' . __('telegram.menu.settings') . '*' . PHP_EOL .
                __('telegram.settings.language') . ': ' . $lang[$this->lang] . PHP_EOL .
                __('telegram.settings.phone_number') . ': ' . $this->user->phone ?? '-',
            'reply_markup' => $reply_markup,
            'parse_mode' => 'Markdown'
        ]);

        $this->updateUserStep($chatId, 'settings');
        $this->storeUserJourney("Настройки");
    }

    public
    function requestPhoneKeyboard(): Keyboard
    {
        return new Keyboard(
            [
                'keyboard' => [[['text' => 'Отправить контакт', 'request_contact' => true]]],
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]
        );
    }

    // Back
    private
    function back(
        $chatId,
        $step
    ): void {
        $stepInfo = $this->user->previousChoice;

        $commands = [
            'show_specializations' => 'selectSpecialization',
            'show_disease_types' => 'selectDiseaseType',
            'show_specializations_top_clinic' => function () use ($chatId) {
                $this->selectSpecialization($chatId, true);
            },
            'show_disease_types_top_clinic' => function () use ($chatId) {
                $this->selectDiseaseType($chatId, true);
            },
            'show_clinic' => function () use ($chatId, $stepInfo) {
                if ($stepInfo && $stepInfo->previous_specialization_id) {
                    $this->clinicList($chatId, $stepInfo->previous_specialization_id, 'specialization');
                } elseif ($stepInfo && $stepInfo->previous_disease_type_id) {
                    $this->clinicList($chatId, $stepInfo->previous_disease_type_id, 'disease_type');
                } else {
                    $this->clinicList($chatId);
                }
            },
            'show_top_clinic' => function () use ($chatId, $stepInfo) {
                if ($stepInfo && $stepInfo->previous_specialization_id) {
                    $this->clinicList($chatId, $stepInfo->previous_specialization_id, 'specialization', true);
                } elseif ($stepInfo && $stepInfo->previous_disease_type_id) {
                    $this->clinicList($chatId, $stepInfo->previous_disease_type_id, 'disease_type', true);
                } else {
                    $this->clinicList($chatId);
                }
            },
            'show_establishment_category' => 'selectEstablishmentCategory'
        ];

        if (array_key_exists($step, $commands)) {
            if (is_callable($commands[$step])) {
                $commands[$step]();
            } else {
                $this->{$commands[$step]}($chatId);
            }
        }

        $events = [
            'show_specializations' => 'Выбор специализации',
            'show_disease_types' => 'Выбор тип болезни',
            'show_clinic' => 'Выбор клиники',
            'show_top_clinic' => 'Выбор топ клиники',
            'show_establishment_category' => 'Выбор категорию заведения'
        ];

        if (array_key_exists($step, $events)) {
            $this->storeUserJourney($events[$step]);
        }
    }

    // User
    private
    function updateUserLang(
        $lang
    ): void {
        $this->user->update(['lang' => $lang]);

        $this->lang = $this->user->lang;
        Session::put('locale', $lang);
        App::setLocale($lang);
    }

    private
    function updateUserStep(
        $chatId,
        $step
    ): void {
        BotUser::query()->updateOrCreate(['chat_id' => $chatId], ['step' => $step]);
    }

    private
    function storeUserJourney(
        $event
    ): void {
        $this->user->journey()->create(['event_name' => $event]);
    }
}
