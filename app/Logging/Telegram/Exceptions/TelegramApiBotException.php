<?php

declare(strict_types=1);

namespace App\Logging\Telegram\Exceptions;

use Illuminate\Http\Request;

final class TelegramApiBotException extends \Exception
{
    /*public function render(Request $request) {
        return response()->json([
            'key'=> 'test',
            'text'=> '123'
        ]);
    }

    public function report() {

    }*/
}