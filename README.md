# Google Chat Logger Channel for Laravel

[![Latest Version](https://img.shields.io/packagist/v/gboquizosanchez/google-chat-logger-channel.svg)](https://packagist.org/packages/gboquizosanchez/google-chat-logger-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/gboquizosanchez/google-chat-logger-channel.svg)](https://packagist.org/packages/gboquizosanchez/google-chat-logger-channel)

A powerful and lightweight Laravel logging channel that sends logs directly to Google Chat spaces via webhooks. Perfect for monitoring your application's health and receiving instant notifications about errors, exceptions, and important events.

## ✨ Features

- 🚀 Real-time log sending to Google Chat
- 📱 Rich formatting with cards and emojis based on log level
- ⚙️ Flexible configuration via environment variables
- 🔧 Support for context and exception details
- 🎯 Compatible with Laravel 10, 11, and 12
- 📊 Smart error handling and retries

## 📦 Installation

Install the package using Composer:
```bash
composer require gboquizosanchez/google-chat-logger-channel
```

## Environment Variables

You can configure the channel by setting the following environment variables:

- `GOOGLE_CHAT_WEBHOOK_URL` - The URL of the Google Chat webhook to send logs to.
- `GOOGLE_CHAT_LOG_LEVEL` - The minimum log level to send to Google Chat. Defaults to `error`.
- `GOOGLE_CHAT_IGNORE_CONTEXTS` - Whether to ignore context when sending logs to Google Chat. Defaults to `true`.
- `GOOGLE_CHAT_IGNORE_EXCEPTIONS` - Whether to ignore exceptions when sending logs to Google Chat. Defaults to `true`.
- `GOOGLE_CHAT_QUEUED` - Whether to queue logs to send to Google Chat. Defaults to `false`.

## Problems? 🚨

Let me know about yours by [opening an issue](https://github.com/gboquizosanchez/google-chat-channel/issues/new)!

## Credits 🧑‍💻

- [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- [All Contributors](../../contributors)

## License 📄

MIT License (MIT). See [License File](LICENSE.md).
