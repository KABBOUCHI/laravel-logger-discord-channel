# Laravel Logger - Discord Channel
###### A Discord based Monolog driver for Laravel

## Install
```bash
composer require kabbouchi/laravel-logger-discord-channel:dev-master

```

## Usage

Add the new driver type in your `config/logging.php` configuration

```php
'channels' => [
    'discord' => [
        'driver' => 'custom',
        'via' => KABBOUCHI\LoggerDiscordChannel\DiscordLogger::class,
        'webhook' => 'https://discordapp.com/api/webhooks/.....',
        'level' => 'DEBUG',
        'role_id' => null, // role to tag in the error,
        'environment' => 'production', // or ['production', 'staging', 'local']
    ],
],
```

## Note
You may need to clear cache after installation if you get `laravel.EMERGENCY: Unable to create configured logger. ... Log [discord] is not defined.` with
```bash
php artisan config:clear
```


