<?php
declare(strict_types=1);

namespace ArturJr\PrestoClient;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;

class LoggerClient
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $template = '{date_common_log} {uri} {req_headers} {req_body} {res_headers}';

    /**
     * LoggerClient constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param callable|null $handler
     *
     * @return ClientInterface
     */
    public function client(callable $handler = null): ClientInterface
    {
        $handlerStack = HandlerStack::create($handler);
        $handlerStack->push(
            Middleware::log($this->logger, new MessageFormatter($this->template))
        );
        return new Client([
            'handler' => $handlerStack,
        ]);
    }

    /**
     * @codeCoverageIgnore
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
}
