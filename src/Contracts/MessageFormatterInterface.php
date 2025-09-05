<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Contracts;

use Monolog\LogRecord;

interface MessageFormatterInterface
{
    public function format(LogRecord $record): array;
}
