<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Contracts;

interface MessageSenderInterface
{
    public function send(array $message): void;
}
