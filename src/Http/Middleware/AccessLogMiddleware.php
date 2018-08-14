<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Http\Middleware
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Http\Middleware;

use CoiSA\Monolog\Log\Processor\ElapsedTimeProcessor;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AccessLogMiddleware
 *
 * @package CoiSA\Monolog\Http\Middleware
 */
class AccessLogMiddleware implements MiddlewareInterface
{
    /**
     * @const string Access log context name
     */
    const DEFAULT_NAME = 'access_log';

    /**
     * @var Logger Monolog instance
     */
    private $logger;

    /**
     * AccessLogMiddleware constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger->withName(self::DEFAULT_NAME);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $logger = $this->configure($request, $this->logger);

        $logger->info('Request', compact('request'));
        $response = $handler->handle($request);
        $logger->info('Response', compact('response'));

        return $response;
    }

    /**
     * Configure middleware processor dependencies
     *
     * @param ServerRequestInterface $request
     * @param Logger $logger
     *
     * @return Logger
     */
    private function configure(ServerRequestInterface $request, Logger $logger): Logger
    {
        $logger->pushProcessor(new ElapsedTimeProcessor());
        $logger->pushProcessor(
            new WebProcessor(
                $request->getServerParams(),
                [
                    'url'         => 'REQUEST_URI',
                    'ip'          => 'REMOTE_ADDR',
                    'http_method' => 'REQUEST_METHOD',
                    'server'      => 'SERVER_NAME',
                    'referrer'    => 'HTTP_REFERER',
                    'user_agent'  => 'HTTP_USER_AGENT',
                ]
            )
        );

        return $logger;
    }
}