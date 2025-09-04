<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Monolog\LogRecord;

interface LoggerSenderInterface
{
    public function send(LogRecord $record): void;
}
