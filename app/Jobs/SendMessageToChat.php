<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;

class SendMessageToChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Api $telegram;
    protected string $chatId;
    protected string $text;
    protected ?string $photoPath;

    /**
     * @throws TelegramSDKException
     */
    public function __construct(string $chatId, string $text, ?string $photoPath = null)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->photoPath = $photoPath;
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    public function handle(): void
    {
        try {
            if ($this->photoPath) {
                $photoPath = Storage::url('public/' . $this->photoPath);
                $fullPhotoUrl = env('APP_URL') . $photoPath;
                $photoFile = InputFile::create($fullPhotoUrl);

                $this->telegram->sendPhoto([
                    'chat_id' => $this->chatId,
                    'photo' => $photoFile,
                    'caption' => $this->text,
                ]);
            } else {
                $this->telegram->sendMessage([
                    'chat_id' => $this->chatId,
                    'text' => $this->text,
                ]);
            }
        } catch (TelegramSDKException $e) {
            Log::error('Failed to send message to chat ID ' . $this->chatId . ': ' . $e->getMessage());
        }
    }
}
