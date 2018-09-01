<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
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
final class AccessLogMiddleware implements MiddlewareInterface
{
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
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $this->logger->info('access_log', compact('request', 'response'));

        return $response;
    }
}
