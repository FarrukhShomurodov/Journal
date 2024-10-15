<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Services\TelegramService;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramController extends Controller
{
    protected Api $telegram;
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegram = new Api(config('telegram.bot_token'));
        $this->telegramService = $telegramService;
    }

    public function handleWebhook(): void
    {
        $update = $this->telegram->getWebhookUpdates();
        $this->telegram->commandsHandler(true);

        // Обработка сообщений
        if ($update->has('message')) {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            $user = BotUser::firstOrCreate(['chat_id' => $chatId]);

            if ($text == '/start') {
                $user->update([
                    'uname' => $message->from->username,
                    'first_name' => $message->from->first_name,
                    'second_name' => $message->from->last_name,
                    'step' => 'choose_language'
                ]);

                $user->previousChoice()->updateOrCreate(
                    ['bot_user_id' => $user->id],
                    [
                        'previous_specialization_id' => null,
                        'previous_disease_type_id' => null,
                        'previous_clinic_id ' => null
                    ]
                );

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
                return;
            }

            if ($update->getMessage()->has('contact')) {
                $phoneNumber = $update->getMessage()->getContact()->getPhoneNumber();
                $user->update([
                    'phone' => $phoneNumber
                ]);

                if ($user->step === 'change_phone') {
                    $user->update([
                        'step' => 'phone_changed'
                    ]);
                }
            } else if ($user->step === 'get_application' || $user->step === 'change_phone') {
                if ((int)$text) {
                    $user->update([
                        'phone' => $text
                    ]);

                    if ($user->step === 'change_phone') {
                        $user->update([
                            'step' => 'phone_changed'
                        ]);
                    }
                } else {
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Введите действительный номер телефона.',
                        'reply_markup' => $this->telegramService->requestPhoneKeyboard(),
                    ]);
                }
            }

            $this->telegramService->processMessage($chatId, $text, $user->step, $message);
        }
    }

}
