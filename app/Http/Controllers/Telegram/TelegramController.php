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
            $user->update(['uname' => $message->from->username]);

            if ($text == '/start') {
                $user->update(['step' => 'choose_language']);

                $user->stepInformation()->updateOrCreate(
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
            } else if (preg_match('/^\+998\d{9}$/', $text)) {
                $user->update([
                    'phone' => $text
                ]);
            }


            $this->telegramService->processMessage($chatId, $text, $user->step, $message);
        }

        // Обработка callback_query
        if ($update->has('callback_query')) {
            $callbackQuery = $update->getCallbackQuery();
            $chatId = $callbackQuery->getMessage()->getChat()->getId();
            $data = $callbackQuery->getData();
            $this->telegramService->processCallbackQuery($chatId, $data, $callbackQuery);
        }
    }

}
