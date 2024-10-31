<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Models\BotUserSession;
use App\Services\TelegramService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

            $user = BotUser::query()->firstOrCreate(['chat_id' => $chatId], ['isactive' => true]);

            if (!$user->isactive) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "К сожалению, ваш доступ заблокирован. Пожалуйста, свяжитесь с поддержкой для получения дополнительной информации. Вы можете написать пользователю @support_team.",
                ]);
                return;
            }

            $user->update(['last_activity' => now()]);

            $lastSession = BotUserSession::query()->where('bot_user_id', $user->id)->latest()->first();

            if (!$lastSession || $lastSession->session_end && Carbon::parse($lastSession->session_end)->diffInMinutes(
                    now()
                ) > 1) {
                BotUserSession::query()->create([
                    'bot_user_id' => $user->id,
                    'session_start' => now(),
                ]);
            } else {
                $lastSession->update(['session_end' => now()]);
            }

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

                $user->journey()->create([
                    'event_name' => 'Старт'
                ]);

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
                $phoneNumber = str_contains((string)$phoneNumber, '+') ? $phoneNumber : '+' . $phoneNumber;
                $user->update([
                    'phone' => $phoneNumber
                ]);

                if ($user->step === 'change_phone') {
                    $user->update([
                        'step' => 'phone_changed'
                    ]);
                }
            } else {
                if ($user->step === 'get_application' || $user->step === 'change_phone') {
                    if ((int)$text) {
                        $text = str_contains((string)$text, '+') ? $text : '+' . $text;
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
            }

            if ($user->lang) {
                App::setLocale($user->lang);
            }

            $user = BotUser::query()->where('chat_id', $chatId)->first();

            $this->telegramService->processMessage($chatId, $text, $user->step, $message, $user);
        }
    }

    public function sendMessageToUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'chat_ids' => 'required|array',
            'text' => 'required|string',
        ]);

        try {
            foreach ($validated['chat_ids'] as $chatId) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $validated['text']
                ]);
            }

            return redirect()->back()->with('success', 'Сообщение успешно отправлено!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ошибка при отправке сообщения: ' . $e->getMessage());
        }
    }

}
