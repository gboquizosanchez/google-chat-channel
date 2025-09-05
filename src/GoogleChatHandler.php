<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Boquizo\GoogleChatChannel\Concerns\ResolvesLogSender;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Override;

final class GoogleChatHandler extends AbstractProcessingHandler
{
    use ResolvesLogSender;

    public function __construct(
        private readonly string $url,
        string|int|Level $level = Level::Error,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    #[Override]
    protected function write(LogRecord $record): void
    {
        $this->resolveLogSender($this->url)->send($record);
    }
}
