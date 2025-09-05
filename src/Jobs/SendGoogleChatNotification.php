<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

final class SendGoogleChatNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected string $url,
        protected array $message,
    ) {}

    public function handle(): void
    {
        Http::post($this->url, $this->message);
    }
}
