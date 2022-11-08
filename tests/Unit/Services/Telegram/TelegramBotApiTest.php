<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;
use Tests\TestCase;

final class TelegramBotApiTest  extends TestCase
{
    /**
     * @test
     * @return void
       **/
    public function it_send_message_success(): void
    {
        // Реальный запрос http
        //Http::allowStrayRequests();

        // Можно делать fake  любые запросы, перечисленыне списком
        Http::fake([
            TelegramBotApi::HOST .'*' =>Http::response(['ok' => true])
        ]);

        $result = TelegramBotApi::sendMessage('',1,'Testing');

        $this->assertTrue($result['ok']);
    }

}