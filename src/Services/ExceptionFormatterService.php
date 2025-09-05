<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Error;
use Exception;

final readonly class ExceptionFormatterService
{
    public function __construct(
        private ConfigurationService $config
    ) {
    }

    public function createSection(array $context): ?array
    {
        if (! isset($context['exception']) || $this->config->shouldIgnoreExceptions()) {
            return null;
        }

        $exceptionDetails = $this->formatException($context['exception']);

        return [
            'widgets' => [
                [
                    'textParagraph' => [
                        'text' => "<b>Exception Details:</b><br>{$exceptionDetails}",
                    ],
                ],
            ],
        ];
    }

    private function formatException(Exception|Error $exception): string
    {
        return sprintf(
            "<b>File:</b> %s:%d\n<b>Trace:</b> %s",
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString(),
        );
    }
}
