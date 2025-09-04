<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\LogRecord;
use Monolog\Level;

class GoogleChatSender implements LoggerSenderInterface
{
    private const string CONFIG_QUEUED = 'logging.channels.google_chat.queued';

    private const array ALLOWED_LEVELS = [
        Level::Emergency,
        Level::Critical,
        Level::Error,
    ];

    public function __construct(
        private readonly string $url,
    ) {
    }

    public function send(LogRecord $record): void
    {
        if (! $this->shouldSendLogRecord($record)) {
            return;
        }

        $message = (new GoogleChatMessageBuilder($record))->buildMessage();

        if ($this->isQueuedDeliveryEnabled()) {
            $this->dispatchQueuedNotification($message);
        } else {
            $this->sendDirectNotification($message);
        }
    }

    private function shouldSendLogRecord(LogRecord $record): bool
    {
        return in_array($record->level, self::ALLOWED_LEVELS, true);
    }

    private function isQueuedDeliveryEnabled(): bool
    {
        return Config::boolean(self::CONFIG_QUEUED, false);
    }

    private function dispatchQueuedNotification(array $message): void
    {
        dispatch(new SendGoogleChatNotification($this->url, $message));
    }

    private function sendDirectNotification(array $message): void
    {
        try {
            Http::post($this->url, $message)->throw();
        } catch (RequestException $e) {
            Log::channel('emergency')
                ->error("Google Chat notification failed: {$e->getMessage()}", [
                    'exception' => $e,
                ]);
        }
    }
}
