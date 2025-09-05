<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Services;

use Carbon\Carbon;
use Filament\Exceptions\NoDefaultPanelSetException;
use Illuminate\Support\Facades\Config;

final readonly class FilamentIntegrationService
{
    public function __construct(
        private ConfigurationService $config
    ) {
    }

    public function shouldShowButton(): bool
    {
        return $this->hasFilamentInstallation()
            && $this->hasLogViewerInstalled()
            && $this->config->getFilamentLogViewerDriver() === 'daily'
            && $this->hasStackDriverWithDaily();
    }

    public function createButtonSection(): array
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
        $logViewerSlug = $this->config->getFilamentLogViewerSlug();
        $today = Carbon::now()->format('Y-m-d');

        return sprintf('%s/%s/%s/%s', $appUrl, $panelPath, $logViewerSlug, $today);
    }

    private function hasFilamentInstallation(): bool
    {
        return class_exists(\Filament\FilamentManager::class);
    }

    private function hasLogViewerInstalled(): bool
    {
        return class_exists(\Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::class);
    }

    private function hasStackDriverWithDaily(): bool
    {
        return in_array('daily', $this->config->getStackChannels(), true);
    }
}
