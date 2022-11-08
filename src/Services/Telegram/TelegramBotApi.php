<?php

declare(strict_types=1);

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Support\Logging\Telegram\Exceptions\TelegramApiBotException;
use Throwable;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot123';

    public static function sendMessage (string $token, int $chatId, string $text) : bool|array
    {
        try {
            $response = Http::get(self::HOST. $token .'/sendMessage',[
                'chat_id'=>$chatId,
                'text'=>$text
            ])->throw()->json();

            return  $response ?? false;

        }catch (Throwable $e){
            Log::info($e->getMessage());
           report (new TelegramApiBotException($e->getMessage()));
           return false;
        }
    }
}
