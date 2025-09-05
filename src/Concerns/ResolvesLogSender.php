<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Concerns;

use Boquizo\GoogleChatChannel\Contracts\LogSenderFactoryInterface;
use Boquizo\GoogleChatChannel\LogSender;

trait ResolvesLogSender
{
    private function resolveLogSender(string $url): LogSender
    {
        return resolve(LogSenderFactoryInterface::class)->create($url);
    }
}
