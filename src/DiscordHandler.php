<?php

namespace KABBOUCHI\LoggerDiscordChannel;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Monolog\Formatter\LineFormatter;
use \Monolog\Logger;
use \Monolog\Handler\AbstractProcessingHandler;

class DiscordHandler extends AbstractProcessingHandler
{
    private $initialized = false;
    private $guzzle;

    private $name;
    private $subname;

    private $webhook;
    private $statement;

    /**
     * MonologDiscordHandler constructor.
     * @param \GuzzleHttp\Client $guzzle
     * @param bool $webhooks
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($webhook, $name, $subname = '', $level = Logger::DEBUG, $bubble = true)
    {
        $this->name = $name;
        $this->subname = $subname;
        $this->guzzle = new \GuzzleHttp\Client();
        $this->webhook = $webhook;
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function write(array $record)
    {
        $formatter = new LineFormatter(null, null, true, true);
        $formatter->includeStacktraces();
        $content = $formatter->format($record);

        // Set up the formatted log
        $log = [
            'embeds' => [
                [
                    'title' => 'Log from ' . $this->name,
                    // Use CSS for the formatter, as it provides the most distinct colouring.
                    'description' => "```css\n" . substr($content, 0, 2030). '```',
                    'color' => 0xE74C3C,
                ],
            ],
        ];

        // Tag a role if configured for it
        if(config('logging.discord.role_id')) $log['content'] = "<@&" . config('logging.discord.role_id') . ">";


        // Send it to discord
        $this->guzzle->request('POST', $this->webhook, [
            RequestOptions::JSON => $log,
        ]);
    }
}
