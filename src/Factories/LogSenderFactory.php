<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Factories;

use Boquizo\GoogleChatChannel\Contracts\LogSenderFactoryInterface;
use Boquizo\GoogleChatChannel\LogSender;
use Boquizo\GoogleChatChannel\Services\ConfigurationService;
use Boquizo\GoogleChatChannel\Services\CriticalLogLevelValidator;
use Boquizo\GoogleChatChannel\Services\GoogleChatMessageFormatter;
use Boquizo\GoogleChatChannel\Services\HttpMessageSender;

final readonly class LogSenderFactory implements LogSenderFactoryInterface
{
    public function __construct(
        private ConfigurationService $config,
        private CriticalLogLevelValidator $validator,
        private GoogleChatMessageFormatter $formatter,
    ) {
    }

    public function create(string $url): LogSender
    {
        return new LogSender(
            $this->validator,
            $this->formatter,
            new HttpMessageSender($url, $this->config),
        );
    }
}
