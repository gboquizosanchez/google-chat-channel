<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Boquizo\GoogleChatChannel\Services\ConfigurationService;
use Monolog\Logger;

final readonly class GoogleChatDriver
{
    public function __construct(
        private ConfigurationService $config,
    ) {
    }

    public function __invoke(): Logger
    {
        return new Logger('google_chat', [
            new GoogleChatHandler(
                $this->config->getGoogleChatUrl(),
                $this->config->getGoogleChatLevel(),
            ),
        ]);
    }
}
