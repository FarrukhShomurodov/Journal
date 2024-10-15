<?php

namespace App\Services;

use App\Models\BotUser;
use App\Models\Category;
use App\Models\Clinic;
use App\Models\Currency;
use App\Models\DiseaseType;
use App\Models\Entertainment;
use App\Models\Establishment;
use App\Models\Hotel;
use App\Models\Promotion;
use App\Models\Specialization;
use App\Models\UsefulInformation;
use Exception;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramService
{
    protected Api $telegram;
    protected $user;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    public function processMessage($chatId, $text, $step, $message)
    {
        $this->user = BotUser::where('chat_id', $chatId);

        $commands = [
            // Clinic
            'Поиск клиники' => 'selectSpecialization',
            'Поиск лечения' => 'selectDiseaseType',
            'Каталог клиник' => 'clinicList',
            'Топ клиники' => 'clinicTop',
            'По специализации' => function () use ($chatId) {
                $this->selectSpecialization($chatId, true);
            },
            'По типу болезни' => function () use ($chatId) {
                $this->selectDiseaseType($chatId, true);
            },
            'Оставить заявку' => 'getApplication',

            // Promotion
            'Акции' => 'selectPromotion',

            // Useful Information
            'Полезная информация' => 'selectUsefulInfo',

            // Hotel
            'Отели' => 'selectHotel',

            // Entertainment
            'Отдых/развлечения' => 'selectEntertainment',

            // Establishment
            'Где поесть?' => 'selectEstablishmentCategory',

            // Currency
            'Калькулятор валют' => 'selectCurrency',

            // Setting
            'Настройки' => 'settingInformation',

            'На главную' => 'showMainMenu',
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

            // Clinic
            case 'show_specializations':
                if ($text == 'Назад') {
                    $this->clinicTop($chatId);
                    break;
                }
                if ($text !== 'На главную') {
                    $this->clinicList($chatId, $text, 'specialization');
                }
                break;
            case 'show_top_specializations':
                if ($text == 'Назад') {
                    $this->clinicTop($chatId);
                    break;
                }
                if ($text !== 'На главную') {
                    $this->clinicList($chatId, $text, 'specialization', true);
                }
                break;
            case 'show_disease_types':
                if ($text == 'Назад') {
                    $this->clinicTop($chatId);
                    break;
                }

                if ($text !== 'На главную') {
                    $this->clinicList($chatId, $text, 'disease_type');
                }

                break;
            case 'show_top_disease_types':
                if ($text == 'Назад') {
                    $this->clinicTop($chatId);
                    break;
                }

                if ($text !== 'На главную') {
                    $this->clinicList($chatId, $text, 'disease_type', true);
                }

                break;
            case 'show_clinic':
                if ($text == 'Назад') {
                    $stepInfo = $this->user->first()->previousChoice;

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
                if ($text == 'Назад') {
                    $stepInfo = $this->user->first()->previousChoice;

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
                if ($text == 'Назад') {
                    $this->back($chatId, 'show_clinic');
                }
                break;
            case 'show_top_clinic_information':
                if ($text == 'Назад') {
                    $this->back($chatId, 'show_top_clinic');
                }
                break;
            case 'get_application':
                $this->getApplication($chatId);
                break;
            case 'store_application':
                $this->storeApplication($chatId, $text);
                break;

            // Promotions
            case 'show_promotions':
                if ($text !== 'На главную') {
                    $this->showPromotionInformation($chatId, $text);
                }
                break;

            // Useful Information
            case 'show_usefulInformation':
                if ($text !== 'На главную') {
                    $this->showUsefulInfoInformation($chatId, $text);
                }
                break;

            // Hotel
            case 'show_hotel':
                if ($text !== 'На главную') {
                    $this->showHotelInformation($chatId, $text);
                }
                break;

            // Entertainment
            case 'show_entertainment':
                if ($text !== 'На главную') {
                    $this->showEntertainmentInformation($chatId, $text);
                }
                break;

            // Establishment
            case 'show_establishment_category':
                if ($text !== 'На главную') {
                    $this->establishmentList($chatId, $text);
                }
                break;
            case 'show_establishment':
                if ($text === 'Назад') {
                    $this->back($chatId, 'show_establishment_category');
                } else {
                    $this->showEstablishmentInformation($chatId, $text);
                }
                break;

            // Currency
            case 'show_currency':
                if ($text !== 'На главную') {
                    $this->showCurrencyInformation($chatId, $text);
                }
                break;

            // Setting
            case 'phone_changed':
                $this->settingInformation($chatId);
                break;

            case 'settings':
                if ($text === 'Язык') {
                    $this->user->update(['step' => 'edit_language']);

                    $keyboard = [
                        ["🇷🇺 Русский", "🇬🇧 English"],
                        ["🇺🇿 O'zbekcha", "🇰🇿 Қазақша"],
                        ["🇹🇯 Тоҷикӣ"]
                    ];

                    $reply_markup = Keyboard::make([
                        'keyboard' => $keyboard,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);

                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "🇷🇺Пожалуйста, выберите язык.\n\n🇺🇿Iltimos, tilni tanlang.\n\n🇬🇧Please choose a language.\n\n🇰🇿Тілді таңдаңыз.\n\n🇹🇯Лутфан забонро интихоб кунед.",
                        'reply_markup' => $reply_markup
                    ]);
                } elseif ($text === 'Номер телефона') {
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "Введите номер телефона",
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
            $isEdit ? $this->settingInformation($chatId) : $this->showMainMenu($chatId);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Произошла ошибка повторите попытку позже.'
            ]);
        }

    }

    private function showMainMenu($chatId): void
    {
        $keyboard = [
            [
                'Поиск клиники',
                'Поиск лечения',
            ],
            [
                'Каталог клиник',
                'Топ клиники',
            ],
            [
                'Акции',
                'Полезная информация',
            ],
            [
                'Отели',
                'Отдых/развлечения',
            ],
            [
                'Где поесть?',
                'Калькулятор валют',
            ],
            [
                'Настройки',
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
            'text' => 'Главное меню',
            'reply_markup' => $reply_markup
        ]);


        $this->user->first()->previousChoice()->updateOrCreate(
            ['bot_user_id' => $this->user->first()->id],
            [
                'previous_specialization_id' => null,
                'previous_disease_type_id' => null,
                'previous_clinic_id ' => null
            ]
        );

        $this->updateUserStep($chatId, 'show_main_menu');
    }

    // Clinic
    private function selectSpecialization($chatId, $isTop = false): void
    {
        $specializations = Specialization::query()->get();

        if ($specializations->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($specializations as $specialization) {
            $keyboard[] = [$specialization->name['ru']];
        }

        if ($this->user->first()->step == 'clinic_top' || $this->user->first()->step == 'show_top_clinic') {
            $keyboard[] = [
                'Назад'
            ];
        } else {
            $keyboard[] = [
                'На главную'
            ];
        }


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите Специализацию',
            'reply_markup' => $reply_markup
        ]);

        $step = $isTop ? 'show_top_specializations' : 'show_specializations';
        $this->updateUserStep($chatId, $step);
    }

    private function selectDiseaseType($chatId, $isTop = false): void
    {
        $diseaseTypes = DiseaseType::query()->get();

        if ($diseaseTypes->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);
            return;
        }

        $keyboard = [];

        foreach ($diseaseTypes as $diseaseType) {
            $keyboard[] = [$diseaseType->name['ru']];
        }

        if ($this->user->first()->step == 'clinic_top' || $this->user->first()->step == 'show_top_clinic') {
            $keyboard[] = [
                'Назад'
            ];
        } else {
            $keyboard[] = [
                'На главную'
            ];
        }

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите тип болезни',
            'reply_markup' => $reply_markup
        ]);

        $step = $isTop ? 'show_top_disease_types' : 'show_disease_types';
        $this->updateUserStep($chatId, $step);
    }

    private function clinicTop($chatId): void
    {
        $keyboard[] = [
            'По типу болезни',
            'По специализации'
        ];

        $keyboard[] = [
            'На главную'
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Топ клиники:',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'clinic_top');
    }

    private function clinicList($chatId, $text = null, $from = null, $isTop = false): void
    {
        if ($from == 'specialization') {
            if (is_integer($text)) {
                $specialization = Specialization::query()->find($text);
            } else {
                $specialization = Specialization::query()->whereJsonContains('name->ru', $text)->first();
            }

            if (!$specialization) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Клиники по данной специализации не найдены.',
                ]);
                return;
            }

            $this->user->first()->previousChoice()->updateOrCreate(
                ['bot_user_id' => $this->user->first()->id],
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
                $diseaseType = DiseaseType::query()->whereJsonContains('name->ru', $text)->first();
            }

            if (!$diseaseType) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Клиники по данной типы болезени не найдены.',
                ]);
                return;
            }

            $this->user->first()->previousChoice()->updateOrCreate(
                ['bot_user_id' => $this->user->first()->id],
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
                'text' => 'Клиники не найдены для этой специализации.',
            ]);
        } else {
            $keyboard = [];

            foreach ($clinics as $clinic) {
                $keyboard[] = [$clinic->name['ru']];
            }

            $keyboard[] = [
                'Назад'
            ];

            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Список клиник:',
                'reply_markup' => $reply_markup
            ]);

            $step = $isTop ? 'show_top_clinic' : 'show_clinic';
            $this->updateUserStep($chatId, $step);
        }
    }

    private function showClinicInformation($chatId, $text, $isTop = false): void
    {
        $clinic = Clinic::query()->whereJsonContains('name->ru', $text)->first();

        if (!$clinic) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по клинике не найдено.',
            ]);
            return;
        }
        $photos = $clinic->images;


        $clinicDescription = $clinic->description ? "*📝 Описание:* _{$clinic->description['ru']}_\n" : '';

        $contacts = '';
        foreach ($clinic->contacts['type'] as $index => $contactType) {
            $contacts .= "• *{$contactType}:* {$clinic->contacts['type_value'][$index]}\n";
        }

        $description = "*{$clinic->name['ru']}*\n\n"
            . "📅 *График работы:* _{$clinic->working_hours}_\n"
            . $clinicDescription
            . "📍 *Локация:* [Сылка]($clinic->location_link)\n\n"
            . "📞 *Контакты:*\n" . $contacts;


        $keyboard[] = [
            'Оставить заявку',
            'Назад'
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
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
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
                'text' => 'Оставить заявку',
                'reply_markup' => $reply_markup,
                'parse_mode' => 'Markdown'
            ]);
        }

        $this->user->first()->previousChoice()->updateOrCreate(
            ['bot_user_id' => $this->user->first()->id],
            ['previous_clinic_id' => $clinic->id]
        );

        $step = $isTop ? 'show_top_clinic_information' : 'show_clinic_information';
        $this->updateUserStep($chatId, $step);
    }

    // Application
    private function getApplication($chatId): void
    {
        if ($this->user->first()->phone) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Напишите заявку.",
            ]);
            $this->updateUserStep($chatId, 'store_application');
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Введите номер телефона",
                'reply_markup' => $this->requestPhoneKeyboard(),
            ]);
            $this->updateUserStep($chatId, 'get_application');
        }
    }

    private function storeApplication($chatId, $text): void
    {
        $clinicId = $this->user->first()->previousChoice->previous_clinic_id;

        try {
            $this->user->first()->application()->create([
                'clinic_id' => $clinicId,
                'text' => $text,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Ваша заявка отправлена. Скоро мы свяжемся с вами.'
            ]);
        } catch (Exception $e) {
            Log::error('Application storage failed: ' . $e->getMessage());

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Что-то пошло не так, повторите попытку.'
            ]);
        }

        $this->showMainMenu($chatId);
    }

    // Promotion
    private function selectPromotion($chatId): void
    {
        $promotions = Promotion::query()->get();

        if ($promotions->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($promotions as $promotion) {
            $keyboard[] = [$promotion->name['ru']];
        }

        $keyboard[] = [
            'На главную'
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите Акцию',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_promotions');
    }

    private function showPromotionInformation($chatId, $text): void
    {
        $promotion = Promotion::query()->whereJsonContains('name->ru', $text)->first();

        if (!$promotion) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по акции не найдено.',
            ]);
            return;
        }
        $photos = $promotion->images;


        $promotionDescription = $promotion->description ? "*📝 Описание:* _{$promotion->description['ru']}_\n" : '';

        $description = "*{$promotion->name['ru']}*\n\n"
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
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
    }

    // UsefulInfo
    private function selectUsefulInfo($chatId): void
    {
        $usefulInformations = UsefulInformation::query()->get();

        if ($usefulInformations->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($usefulInformations as $usefulInformation) {
            $keyboard[] = [$usefulInformation->name['ru']];
        }

        $keyboard[] = [
            'На главную'
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите статью',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_usefulInformation');
    }

    private function showUsefulInfoInformation($chatId, $text): void
    {
        $usefulInformation = UsefulInformation::query()->whereJsonContains('name->ru', $text)->first();

        if (!$usefulInformation) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по полезным информациям не найдено.',
            ]);
            return;
        }
        $photos = $usefulInformation->images;


        $promotionDescription = $usefulInformation->description ? "*📝 Описание:* _{$usefulInformation->description['ru']}_\n" : '';

        $description = "*{$usefulInformation->name['ru']}*\n\n"
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
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
    }

    // Hotel
    private function selectHotel($chatId): void
    {
        $hotels = Hotel::query()->get();

        if ($hotels->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($hotels as $hotel) {
            $keyboard[] = [$hotel->name['ru']];
        }

        $keyboard[] = [
            'На главную'
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите Отель',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_hotel');
    }

    private function showHotelInformation($chatId, $text): void
    {
        $hotel = Hotel::query()->whereJsonContains('name->ru', $text)->first();

        if (!$hotel) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по отелю не найдено.',
            ]);
            return;
        }
        $photos = $hotel->images;


        $clinicDescription = $hotel->description ? "*📝 Описание:* _{$hotel->description['ru']}_\n" : '';

        $contacts = '';
        foreach ($hotel->contacts['type'] as $index => $contactType) {
            $contacts .= "• *{$contactType}:* {$hotel->contacts['type_value'][$index]}\n";
        }

        $description = "*{$hotel->name['ru']}*\n\n"
            . "📅 *График работы:* _{$hotel->working_hours}_\n"
            . $clinicDescription
            . "📍 *Локация:* [Сылка]($hotel->location_link)\n\n"
            . "📞 *Контакты:*\n" . $contacts;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
    }

    // Entertainment
    private function selectEntertainment($chatId): void
    {
        $entertainments = Entertainment::query()->get();

        if ($entertainments->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($entertainments as $entertainment) {
            $keyboard[] = [$entertainment->name['ru']];
        }

        $keyboard[] = [
            'На главную'
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите развлечения',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_entertainment');
    }

    private function showEntertainmentInformation($chatId, $text): void
    {
        $entertainment = Entertainment::query()->whereJsonContains('name->ru', $text)->first();

        if (!$entertainment) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по развлечениям не найдено.',
            ]);
            return;
        }
        $photos = $entertainment->images;


        $clinicDescription = $entertainment->description ? "*📝 Описание:* _{$entertainment->description['ru']}_\n" : '';

        $contacts = '';
        foreach ($entertainment->contacts['type'] as $index => $contactType) {
            $contacts .= "• *{$contactType}:* {$entertainment->contacts['type_value'][$index]}\n";
        }

        $description = "*{$entertainment->name['ru']}*\n\n"
            . "📅 *График работы:* _{$entertainment->working_hours}_\n"
            . $clinicDescription
            . "📍 *Локация:* [Сылка]($entertainment->location_link)\n\n"
            . "📞 *Контакты:*\n" . $contacts;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
    }

    // Establishment
    private function selectEstablishmentCategory($chatId): void
    {
        $categories = Category::query()->get();

        if ($categories->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($categories as $category) {
            $keyboard[] = [$category->name['ru']];
        }

        $keyboard[] = [
            'На главную'
        ];


        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите категорию',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_establishment_category');
    }

    private function establishmentList($chatId, $text): void
    {
        $category = Category::query()->whereJsonContains('name->ru', $text)->first();
        $establishments = $category->establishments;

        if ($establishments->isEmpty()) {
            $this->selectEstablishmentCategory($chatId);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Заведение не найдены для этой категории.',
            ]);
        } else {
            $keyboard = [];

            foreach ($establishments as $establishment) {
                $keyboard[] = [$establishment->name['ru']];
            }

            $keyboard[] = [
                'Назад'
            ];

            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Список заведений:',
                'reply_markup' => $reply_markup
            ]);

            $this->updateUserStep($chatId, 'show_establishment');
        }
    }

    private function showEstablishmentInformation($chatId, $text): void
    {
        $establishment = Establishment::query()->whereJsonContains('name->ru', $text)->first();

        if (!$establishment) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Информация по заведению не найдено.',
            ]);
            return;
        }
        $photos = $establishment->images;


        $clinicDescription = $establishment->description ? "*📝 Описание:* _{$establishment->description['ru']}_\n" : '';

        $contacts = '';
        foreach ($establishment->contacts['type'] as $index => $contactType) {
            $contacts .= "• *{$contactType}:* {$establishment->contacts['type_value'][$index]}\n";
        }

        $description = "*{$establishment->name['ru']}*\n\n"
            . "📅 *График работы:* _{$establishment->working_hours}_\n"
            . $clinicDescription
            . "📍 *Локация:* [Сылка]($establishment->location_link)\n\n"
            . "📞 *Контакты:*\n" . $contacts;

        if (count($photos) === 0) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $description,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $mediaGroup = [];
            foreach ($photos as $index => $photo) {
//            $photoPath = Storage::url('public/' . $photo->url);
//            $fullPhotoUrl = env('APP_URL') . $photoPath;

                $mediaGroup[] = [
                    'type' => 'photo',
                    'media' => 'https://sitspaceuz.uz/storage/stadium_photos/Ds3Oveiw6IWb43t2V60iX7T0axg1iusDVX6i6voK.jpg',
                    'caption' => $index === 0 ? $description : '',
                    'parse_mode' => 'Markdown'
                ];
            }

            $this->telegram->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => json_encode($mediaGroup)
            ]);
        }
    }

    // Currency
    private function selectCurrency($chatId): void
    {
        $currencies = Currency::query()->get();

        if ($currencies->isEmpty()) {
            $this->updateUserStep($chatId, 'show_main_menu');

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Пусто',
            ]);

            return;
        }

        $keyboard = [];

        foreach ($currencies as $currency) {
            $keyboard[] = [$currency->ccy];
        }

        $keyboard[] = [
            'На главную'
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => false
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите валюту',
            'reply_markup' => $reply_markup
        ]);

        $this->updateUserStep($chatId, 'show_currency');
    }

    private function showCurrencyInformation($chatId, $text): void
    {
        $currency = Currency::query()->where('ccy', $text)->first();

        $information = "💱 *{$currency->ccy}*\n\n"
            . "💳 *Код:* _{$currency->code}_\n"
            . "💵 *Курс:* _{$currency->rate}_\n"
            . "📅 *Дата:* _{$currency->relevance_date}_";

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $information,
            'parse_mode' => 'Markdown'
        ]);
    }

    // Setting
    private function settingInformation($chatId): void
    {
        $keyboard[] = [
            'Язык',
            'Номер телефона',
        ];

        $keyboard[] = [
            'На главную',
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
            'text' => '*Настройки*' . PHP_EOL .
                'Язык' . ': ' . $lang[$this->user->first()->lang] . PHP_EOL .
                'Номер телефона' . ': ' . $this->user->first()->phone ?? '-',
            'reply_markup' => $reply_markup,
            'parse_mode' => 'Markdown'
        ]);

        $this->updateUserStep($chatId, 'settings');
    }

    public function requestPhoneKeyboard(): Keyboard
    {
        return new Keyboard(['keyboard' => [[['text' => 'Отправить контакт', 'request_contact' => true]]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
    }

    // Back
    private function back($chatId, $step): void
    {
        $stepInfo = $this->user->first()->previousChoice;

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
    }

    // User
    private function updateUserLang($lang): void
    {
        $this->user->update(['lang' => $lang]);
    }

    private function updateUserStep($chatId, $step): void
    {
        BotUser::query()->updateOrCreate(['chat_id' => $chatId], ['step' => $step]);
    }

}
