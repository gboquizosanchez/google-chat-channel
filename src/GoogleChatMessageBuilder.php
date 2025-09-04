<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Carbon\Carbon;
use Filament\Exceptions\NoDefaultPanelSetException;
use Illuminate\Support\Facades\Config;
use JsonException;
use Monolog\Level;
use Monolog\LogRecord;

class GoogleChatMessageBuilder
{
    private const string IGNORE_EXCEPTIONS = 'logging.channels.google_chat.ignore_exceptions';
    private const string IGNORE_CONTEXTS = 'logging.channels.google_chat.ignore_contexts';
    private const string LOG_VIEWER_DRIVER = 'filament-log-viewer.driver';
    private const string LOG_VIEWER_SLUG = 'filament-log-viewer.resource.slug';

    private const array LEVEL_EMOJIS = [
        Level::Emergency->value => '🐞',
        Level::Critical->value => '💔',
        Level::Error->value => '❌',
    ];

    public function __construct(
        private readonly LogRecord $record,
    ) {
    }

    public function buildMessage(): array
    {
        return [
            'cards' => [
                [
                    'header' => $this->buildHeader(),
                    'sections' => $this->buildSections(),
                ],
            ],
        ];
    }

    private function buildHeader(): array
    {
        return [
            'title' => $this->buildTitle(),
            'subtitle' => $this->buildSubtitle(),
        ];
    }

    private function buildTitle(): string
    {
        $emoji = $this->getEmojiForLevel($this->record->level);
        $levelName = $this->record->level->getName();
        $appName = Config::string('app.name', 'Laravel');

        return sprintf('%s [%s] %s', $emoji, $levelName, $appName);
    }

    private function getEmojiForLevel(Level $level): string
    {
        return self::LEVEL_EMOJIS[$level->value] ?? '';
    }

    private function buildSubtitle(): string
    {
        $environment = Config::string('app.env', 'production');
        $datetime = $this->record->datetime->format('Y-m-d H:i:s');

        return sprintf('%s - %s', $environment, $datetime);
    }

    private function buildSections(): array
    {
        $sections = [
            $this->createMessageSection(),
        ];

        $conditionalSections = [
            $this->createContextSection(),
            $this->createExceptionSection(),
        ];

        $sections = [
            ...$sections,
            ...array_filter($conditionalSections),
        ];

        if ($this->shouldAddFilamentButton()) {
            $sections[] = array_filter($this->createFilamentButtonSection());
        }

        return $sections;
    }

    private function createMessageSection(): array
    {
        return [
            'widgets' => [
                [
                    'textParagraph' => [
                        'text' => $this->record->message,
                    ],
                ],
            ],
        ];
    }

    private function createContextSection(): ?array
    {
        if (Config::boolean(self::IGNORE_CONTEXTS, false)) {
            return null;
        }

        $context = $this->extractContextData();
        if ($context === []) {
            return null;
        }

        return [
            'widgets' => [
                [
                    'textParagraph' => [
                        'text' => $this->formatContextAsJson($context),
                    ],
                ],
            ],
        ];
    }

    private function extractContextData(): array
    {
        $context = $this->record->context;

        unset($context['exception']);

        return $context;
    }

    private function formatContextAsJson(array $context): string
    {
        try {
            $contextJson = json_encode(
                $context,
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT,
            );
            return "<b>Context:</b>\n{$contextJson}";
        } catch (JsonException $e) {
            return "Error encoding context: {$e->getMessage()}";
        }
    }

    private function createExceptionSection(): ?array
    {
        $exceptionDetails = $this->formatException();

        if ($exceptionDetails === '') {
            return null;
        }

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

    private function formatException(): string
    {
        if (Config::boolean(self::IGNORE_EXCEPTIONS, false)) {
            return '';
        }

        $exception = $this->record->context['exception'] ?? null;
        if ($exception === null) {
            return '';
        }

        return sprintf(
            "<b>File:</b> %s:%d\n<b>Trace:</b> %s",
            $exception->getFile() ?? $exception['file'] ?? 'N/A',
            $exception->getLine() ?? $exception['line'] ?? 'N/A',
            $exception->getTraceAsString() ?? $exception['trace'] ?? 'N/A'
        );
    }

    private function shouldAddFilamentButton(): bool
    {
        $hasFilament = class_exists(\Filament\FilamentManager::class);
        $installedFilamentLogViewer = class_exists(\Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::class);
        $hasLogDaily = Config::string(self::LOG_VIEWER_DRIVER, 'daily') === 'daily';
        $logStackHasDailyDriver = in_array('daily', Config::get('logging.channels.stack.channels', []), true);

        return $hasFilament
            && $installedFilamentLogViewer
            && $hasLogDaily
            && $logStackHasDailyDriver;
    }

    private function createFilamentButtonSection(): array
    {
        try {
            return [
                'widgets' => [
                    [
                        'buttons' => [
                            [
                                'textButton' => [
                                    'text' => 'See Log File',
                                    'onClick' => [
                                        'openLink' => [
                                            'url' => $this->generateLogUrl(),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        } catch (NoDefaultPanelSetException) {
            return [];
        }
    }

    /**
     * @throws \Filament\Exceptions\NoDefaultPanelSetException
     */
    private function generateLogUrl(): string
    {
        $appUrl = Config::string('app.url');
        $panelPath = \Filament\Facades\Filament::getDefaultPanel()->getPath();
        $logViewerSlug = Config::string(self::LOG_VIEWER_SLUG, 'log-viewer');
        $today = Carbon::now()->format('Y-m-d');

        return sprintf('%s/%s/%s/%s', $appUrl, $panelPath, $logViewerSlug, $today);
    }
}
