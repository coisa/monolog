<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Http\Middleware
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Http\Middleware;

use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Class LoggerAwareMiddleware
 *
 * @package CoiSA\Monolog\Http\Middleware
 */
class LoggerAwareMiddleware implements MiddlewareInterface
{
    /**
     * @var Logger Monolog instance
     */
    private $logger;

    /**
     * LoggerAwareMiddleware constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set logger to aware request handlers
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($handler instanceof LoggerAwareInterface) {
            $handler->setLogger($this->logger);
        }

        return $handler->handle($request);
    }
}