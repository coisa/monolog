<?php
/***
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\Http\Middleware\LoggerAwareMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareMiddlewareFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class LoggerAwareMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LoggerAwareMiddleware
     */
    public function __invoke(ContainerInterface $container): LoggerAwareMiddleware
    {
        $logger = $container->get(LoggerInterface::class);

        return new LoggerAwareMiddleware($logger);
    }
}
