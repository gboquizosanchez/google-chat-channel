<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Override;

class GoogleChatLogger extends AbstractProcessingHandler
{
    protected GoogleChatSender $sender;

    public function __construct(
        string $url,
        string|int|Level $level = Level::Error,
        bool $bubble = true,
    ) {
        $this->sender = new GoogleChatSender($url);

        parent::__construct($level, $bubble);
    }

    #[Override]
    protected function write(LogRecord $record): void
    {
        $this->sender->send($record);
    }
}
