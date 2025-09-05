<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Boquizo\GoogleChatChannel\Contracts\LogLevelValidatorInterface;
use Monolog\Level;

final class CriticalLogLevelValidator implements LogLevelValidatorInterface
{
    /** @var array<int, Level> */
    private const array ALLOWED_LEVELS = [
        Level::Emergency,
        Level::Critical,
        Level::Error,
    ];

    public function isAllowed(Level $level): bool
    {
        return in_array($level, self::ALLOWED_LEVELS, true);
    }
}
