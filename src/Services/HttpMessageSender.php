<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Boquizo\GoogleChatChannel\Contracts\MessageSenderInterface;
use Boquizo\GoogleChatChannel\Jobs\SendGoogleChatNotification;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final readonly class HttpMessageSender implements MessageSenderInterface
{
    public function __construct(
        private string $url,
        private ConfigurationService $config
    ) {
    }

    public function send(array $message): void
    {
        if ($this->config->isQueueEnabled()) {
            $this->sendQueued($message);
        } else {
            $this->sendDirect($message);
        }
    }

    private function sendQueued(array $message): void
    {
        dispatch(new SendGoogleChatNotification($this->url, $message));
    }

    private function sendDirect(array $message): void
    {
        try {
            Http::post($this->url, $message)->throw();
        } catch (RequestException|ConnectionException $e) {
            $this->registerIntoEmergencyLog($e);
        }
    }

    private function registerIntoEmergencyLog(Exception $e): void
    {
        Log::channel('emergency')
            ->error('Google Chat notification failed: '.$e->getMessage());
    }
}
