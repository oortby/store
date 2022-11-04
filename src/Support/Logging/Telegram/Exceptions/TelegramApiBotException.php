<?php

declare(strict_types=1);

namespace Support\Logging\Telegram\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class TelegramApiBotException extends \Exception
{
    public function render(Request $request) {
        Log::info('TelegramApiBotException- render');
        /*return response()->json([
            'key'=> 'test',
            'text'=> '123',
        ]);*/
    }

    public function report() {
        Log::info('TelegramApiBotException- report');
    }
}