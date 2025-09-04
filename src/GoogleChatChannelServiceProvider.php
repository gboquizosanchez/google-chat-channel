<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Override;

class GoogleChatChannelServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/google-chat-channel.php',
            'logging.channels.google_chat'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/google-chat-channel.php' => config_path('google-chat-channel.php'),
        ], 'config');

        if (version_compare(Application::VERSION, '11.0.0', '<')) {
            $this->polyfills();
        }
    }

    private function polyfills(): void
    {
        Repository::macro('string', function (string $key, mixed $default = null): string {
            $value = $this->get($key, $default);

            return (string) Str::of($value);
        });

        Repository::macro('boolean', function (string $key, bool $default = false): bool {
            $value = $this->get($key, $default);

            return is_bool($value) ? $value : (bool) $value;
        });
    }
}
