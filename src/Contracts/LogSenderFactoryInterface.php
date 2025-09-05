<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Contracts;

use Boquizo\GoogleChatChannel\LogSender;

interface LogSenderFactoryInterface
{
    public function create(string $url): LogSender;
}
