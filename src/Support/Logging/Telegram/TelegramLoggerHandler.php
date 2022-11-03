<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

final class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;

    protected string $token;

    public function __construct(array $config)
    {

        $level = Logger::toMonologLevel($config['level']);
        $this->chatId = (int)$config['chat_id'];
        $this->token = (string)$config['token'];
        parent::__construct($level);
    }

    protected function  write(array $record): void
    {
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $record['formatted']
        );
    }

}
