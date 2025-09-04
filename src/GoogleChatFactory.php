<?php

declare(strict_types=1);

namespace Boquizo\GoogleChatChannel;

use Illuminate\Support\Facades\Config;
use Monolog\Logger;

class GoogleChatFactory
{
    public function __invoke(): Logger
    {
        $url = Config::string('logging.channels.google_chat.url', '');
        $level = Config::string('logging.channels.google_chat.level', 'error');

        $handler = new GoogleChatLogger($url, $level);

        return new Logger('google_chat', [$handler]);
    }
}
