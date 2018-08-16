<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Http\Middleware
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareMiddleware
 *
 * @package CoiSA\Monolog\Http\Middleware
 */
class LoggerAwareMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface Logger instance
     */
    private $logger;

    /**
     * LoggerAwareMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
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