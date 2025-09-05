<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Contracts;

use Monolog\Level;

interface LogLevelValidatorInterface
{
    public function isAllowed(Level $level): bool;
}
