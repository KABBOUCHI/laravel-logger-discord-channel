<?php

namespace KABBOUCHI\LoggerDiscordChannel;

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

        
       $this->guzzle->request('POST', $this->webhook, [
            'form_params' => [
                'content' => env('APP_URL') . " on fire 🔥 \n\n" . substr($content, 0, 1900)
            ]
        ]);
    }
}
