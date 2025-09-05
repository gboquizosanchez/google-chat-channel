<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Boquizo\GoogleChatChannel\Contracts\MessageSenderInterface;
use Boquizo\GoogleChatChannel\Contracts\MessageFormatterInterface;
use Boquizo\GoogleChatChannel\Contracts\LogLevelValidatorInterface;
use Monolog\LogRecord;

final readonly class LogSender
{
    public function __construct(
        private LogLevelValidatorInterface $levelValidator,
        private MessageFormatterInterface $messageFormatter,
        private MessageSenderInterface $messageSender,
    ) {}

    public function send(LogRecord $record): void
    {
        if (! $this->levelValidator->isAllowed($record->level)) {
            return;
        }

        $this->messageSender->send(
            $this->messageFormatter->format($record),
        );
    }
}
