<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
|  Google Chat Log Channel Configuration
|--------------------------------------------------------------------------
|
| This file is used to configure the Google Chat logging channel. You can
| set the webhook URL and the minimum log level for messages to be sent
| to your Google Chat space. All configuration values can be overridden
| using environment variables.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Driver Configuration
    |--------------------------------------------------------------------------
    |
    | The driver and factory class used to create the Google Chat logger.
    |
    */

    'driver' => 'custom',
    'via' => \Boquizo\GoogleChatChannel\GoogleChatDriver::class,


    /*
    |--------------------------------------------------------------------------
    | Webhook URL
    |--------------------------------------------------------------------------
    |
    | The Google Chat webhook URL where log messages will be sent.
    | You can get this URL from your Google Chat space settings.
    |
    */

    'url' => env('GOOGLE_CHAT_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Log Level
    |--------------------------------------------------------------------------
    |
    | The minimum log level for messages to be sent to Google Chat.
    | Available levels: emergency, critical, error
    |
    */

    'level' => env('GOOGLE_CHAT_LOG_LEVEL', 'error'),

    /*
    |--------------------------------------------------------------------------
    | Ignore Exceptions
    |--------------------------------------------------------------------------
    |
    | When enabled, exceptions will not be included in the log message context.
    |
    */

    'ignore_exceptions' => env('GOOGLE_CHAT_IGNORE_EXCEPTIONS', true),

    /*
    |--------------------------------------------------------------------------
    | Ignore Contexts
    |--------------------------------------------------------------------------
    |
    | When enabled, additional context data will not be included in messages.
    |
    */

    'ignore_contexts' => env('GOOGLE_CHAT_IGNORE_CONTEXTS', true),

    /*
    |--------------------------------------------------------------------------
    | Queued Processing
    |--------------------------------------------------------------------------
    |
    | When enabled, log messages will be processed asynchronously using queues.
    | This can improve application performance for high-volume logging.
    |
    */

    'queued' => env('GOOGLE_CHAT_QUEUED', false),
];
