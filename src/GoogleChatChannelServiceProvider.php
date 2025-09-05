<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Boquizo\GoogleChatChannel\Contracts\LogSenderFactoryInterface;
use Boquizo\GoogleChatChannel\Factories\LogSenderFactory;
use Boquizo\GoogleChatChannel\Services\ConfigurationService;
use Boquizo\GoogleChatChannel\Services\ContextFormatterService;
use Boquizo\GoogleChatChannel\Services\CriticalLogLevelValidator;
use Boquizo\GoogleChatChannel\Services\ExceptionFormatterService;
use Boquizo\GoogleChatChannel\Services\FilamentIntegrationService;
use Boquizo\GoogleChatChannel\Services\GoogleChatMessageFormatter;
use Illuminate\Foundation\Application as App;
use Illuminate\Support\ServiceProvider;
use Override;

final class GoogleChatChannelServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(ConfigurationService::class);
        $this->app->singleton(CriticalLogLevelValidator::class);

        $this->app->bind(
            GoogleChatMessageFormatter::class,
            function (App $app): GoogleChatMessageFormatter {
                $config = $app->make(ConfigurationService::class);

                return new GoogleChatMessageFormatter(
                    $config,
                    new ExceptionFormatterService($config),
                    new ContextFormatterService($config),
                    new FilamentIntegrationService($config),
                );
            },
        );

        $this->app->bind(
            LogSenderFactoryInterface::class,
            LogSenderFactory::class,
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/google-chat-channel.php',
            'logging.channels.google_chat'
        );
    }

    public function boot(): void
    {
        $configFile = 'google-chat-channel.php';

        $this->publishes([
            __DIR__ . "/../config/{$configFile}" => config_path($configFile),
        ], 'config');
    }
}
