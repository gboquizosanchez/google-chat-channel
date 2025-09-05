<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use JsonException;

final readonly class ContextFormatterService
{
    public function __construct(
        private ConfigurationService $config
    ) {
    }

    public function createSection(array $context): ?array
    {
        if ($this->config->shouldIgnoreContexts()) {
            return null;
        }

        $filteredContext = $this->removeException($context);
        if ($filteredContext === []) {
            return null;
        }

        $contextText = $this->encodeContext($filteredContext);

        return [
            'widgets' => [
                [
                    'textParagraph' => [
                        'text' => "<b>Context:</b>\n{$contextText}",
                    ],
                ],
            ],
        ];
    }

    private function removeException(array $context): array
    {
        unset($context['exception']);

        return $context;
    }

    private function encodeContext(array $context): string
    {
        try {
            return json_encode(
                $context,
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT,
            );
        } catch (JsonException $e) {
            return "Error encoding context: {$e->getMessage()}";
        }
    }
}
