<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Boquizo\GoogleChatChannel\Contracts\MessageFormatterInterface;
use Monolog\Level;
use Monolog\LogRecord;

final readonly class GoogleChatMessageFormatter implements MessageFormatterInterface
{
    /** @var array<string, string> */
    private const array LEVEL_EMOJIS = [
        Level::Emergency->value => '🐞',
        Level::Critical->value => '💔',
        Level::Error->value => '❌',
    ];

    public function __construct(
        private ConfigurationService $config,
        private ExceptionFormatterService $exceptionFormatter,
        private ContextFormatterService $contextFormatter,
        private FilamentIntegrationService $filamentService,
    ) {
    }

    public function format(LogRecord $record): array
    {
        return [
            'cards' => [
                [
                    'header' => $this->createHeader($record),
                    'sections' => [
                        $this->createMessageSection($record->message),
                        $this->getOptionalSections($record),
                    ],
                ],
            ],
        ];
    }

    private function getOptionalSections(LogRecord $record): array
    {
        $sections = [
            $this->exceptionFormatter->createSection($record->context),
            $this->contextFormatter->createSection($record->context)
        ];

        if ($this->filamentService->shouldShowButton()) {
            $sections[] = $this->filamentService->createButtonSection();
        }

        return array_values(array_filter($sections));
    }

    private function createMessageSection(string $message): array
    {
        return [
            'widgets' => [
                [
                    'textParagraph' => ['text' => $message],
                ],
            ],
        ];
    }

    private function createHeader(LogRecord $record): array
    {
        return [
            'title' => sprintf(
                '%s [%s] %s',
                $this->getEmojiForLevel($record->level),
                $record->level->getName(),
                $this->config->getAppName()
            ),
            'subtitle' => sprintf(
                '%s - %s',
                $this->config->getEnvironment(),
                $record->datetime->format('Y-m-d H:i:s')
            ),
        ];
    }

    private function getEmojiForLevel(Level $level): string
    {
        return self::LEVEL_EMOJIS[$level->value] ?? '';
    }
}
