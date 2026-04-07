<div align="center">

<img src="https://raw.githubusercontent.com/twitter/twemoji/master/assets/svg/1f4ac.svg" width="100" alt="Google Chat Channel">

# `gboquizosanchez/google-chat-channel`

**Laravel logging channel for Google Chat**

[![Latest Stable Version](https://img.shields.io/packagist/v/gboquizosanchez/google-chat-channel.svg)](https://packagist.org/packages/gboquizosanchez/google-chat-channel)
[![Total Downloads](https://img.shields.io/packagist/dt/gboquizosanchez/google-chat-channel.svg)](https://packagist.org/packages/gboquizosanchez/google-chat-channel)
[![PHP](https://img.shields.io/badge/PHP-%5E8.3-777BB4?logo=php&logoColor=white)](https://packagist.org/packages/gboquizosanchez/google-chat-channel)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?logo=laravel&logoColor=white)](https://packagist.org/packages/gboquizosanchez/google-chat-channel)
[![License: MIT](https://img.shields.io/badge/License-MIT-22C55E.svg)](LICENSE.md)

---

*Get instant alerts in Google Chat when your Laravel app logs an error. No setup beyond a webhook URL.*

</div>

---

## Overview

A lightweight Laravel logging channel that sends log entries directly to a Google Chat space via webhooks. Messages are formatted as rich cards with emojis based on log level — so your team can triage errors at a glance without leaving Chat.

---

## ✨ Features

- 🚀 **Real-time delivery** — Log entries sent instantly to your Google Chat space
- 📱 **Rich formatting** — Cards with emojis and severity based on log level
- ⚙️ **Env-based config** — No config file to publish, just `.env` variables
- 🔧 **Context & exceptions** — Optionally include extra context and exception details
- 📊 **Smart retries** — Built-in error handling for webhook failures
- 🔁 **Queue support** — Optionally queue log dispatch to avoid blocking requests

---

## 📦 Installation

```bash
composer require gboquizosanchez/google-chat-channel
```

---

## ⚙️ Configuration

Add the channel to your `config/logging.php`:

```php
'channels' => [
    'google-chat' => [
        'driver' => 'custom',
        'via'    => \Boquizo\GoogleChatChannel\GoogleChatLogger::class,
    ],

    'stack' => [
        'driver'   => 'stack',
        'channels' => ['single', 'google-chat'],
    ],
],
```

Then set your webhook URL in `.env`:

```env
GOOGLE_CHAT_WEBHOOK_URL=https://chat.googleapis.com/v1/spaces/XXX/messages?key=YYY&token=ZZZ
```

> To get a webhook URL, open a Google Chat space → **Manage webhooks** → **Add webhook**.

### Available environment variables

| Variable | Default | Description |
|---|---|---|
| `GOOGLE_CHAT_WEBHOOK_URL` | — | **Required.** Google Chat incoming webhook URL |
| `GOOGLE_CHAT_LOG_LEVEL` | `error` | Minimum log level to forward (`debug`, `info`, `warning`, `error`, ...) |
| `GOOGLE_CHAT_IGNORE_CONTEXTS` | `true` | Exclude context data from the message |
| `GOOGLE_CHAT_IGNORE_EXCEPTIONS` | `true` | Exclude exception details from the message |
| `GOOGLE_CHAT_QUEUED` | `false` | Queue log dispatch instead of sending synchronously |

---

## 🚀 Usage

Once configured, use Laravel's standard logging — no changes needed in your code:

```php
// Sent to Google Chat (at or above your configured level)
Log::error('Payment failed', ['order_id' => 1234]);
Log::critical('Database connection lost');

// Not sent if GOOGLE_CHAT_LOG_LEVEL=error
Log::info('User logged in');
```

Each message arrives in Chat as a formatted card showing the level, message, and optionally context or exception details.

---

## Contributing

Contributions are welcome!

- 🐛 **Report bugs** via [GitHub Issues](https://github.com/gboquizosanchez/google-chat-channel/issues/new)
- 💡 **Suggest features** or improvements
- 🔧 **Submit pull requests** with fixes or enhancements

---

## Credits

- **Author**: [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- **Contributors**: [View all contributors](../../contributors)

---

## 📄 License

This package is open-source software licensed under the [MIT License](LICENSE.md).
