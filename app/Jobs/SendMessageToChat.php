<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class SendMessageToChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Api $telegram;
    protected string $chatId;
    protected string $text;

    /**
     * @throws TelegramSDKException
     */
    public function __construct(string $chatId, string $text)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    public function handle(): void
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $this->text,
            ]);
        } catch (TelegramSDKException $e) {
            Log::error('Failed to send message to chat ID ' . $this->chatId . ': ' . $e->getMessage());
        }
    }
}
