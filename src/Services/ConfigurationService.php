<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Illuminate\Support\Facades\Config;

final class ConfigurationService
{
    public function isQueueEnabled(): bool
    {
        return Config::boolean('logging.channels.google_chat.queued', false);
    }

    public function shouldIgnoreExceptions(): bool
    {
        return Config::boolean('logging.channels.google_chat.ignore_exceptions', false);
    }

    public function shouldIgnoreContexts(): bool
    {
        return Config::boolean('logging.channels.google_chat.ignore_contexts', false);
    }

    public function getAppName(): string
    {
        return Config::string('app.name', 'Laravel');
    }

    public function getEnvironment(): string
    {
        return Config::string('app.env', 'production');
    }

    public function getAppUrl(): string
    {
        return Config::string('app.url');
    }

    public function getGoogleChatUrl(): string
    {
        return Config::string('logging.channels.google_chat.url', '');
    }

    public function getGoogleChatLevel(): string
    {
        return Config::string('logging.channels.google_chat.level', 'error');
    }

    public function getFilamentLogViewerDriver(): string
    {
        return Config::string('filament-log-viewer.driver', 'daily');
    }

    public function getFilamentLogViewerSlug(): string
    {
        return Config::string('filament-log-viewer.resource.slug', 'log-viewer');
    }

    public function getStackChannels(): array
    {
        return Config::array('logging.channels.stack.channels', []);
    }
}
