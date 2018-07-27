<?php

namespace KABBOUCHI\LoggerDiscordChannel;

use Monolog\Logger;

class DiscordLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $log = new Logger('discord');
        $log->pushHandler(new DiscordHandler($config['webhook'], config('app.name'), null, $config['level'] ?? 'DEBUG'), $config['role_id'] ?? null);

        return $log;
    }
}
