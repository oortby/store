<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Logging\Telegram\Exceptions\TelegramApiBotException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot123';

    public static function sendMessage (string $token, int $chatId, string $text) : ?bool
    {
        try {
            $response = Http::get(self::HOST. $token .'/sendMessage',[
                'chat_id'=>$chatId,
                'text'=>$text
            ])->throw()->json();
            return  $response ?? false;

        }catch (Throwable $e){
           report (new TelegramApiBotException($e->getMessage()));
           return false;
        }
    }
}
