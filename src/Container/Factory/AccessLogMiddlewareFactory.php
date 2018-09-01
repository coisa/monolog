<?php
/***
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\Http\Middleware\AccessLogMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AccessLogMiddlewareFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class AccessLogMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return AccessLogMiddleware
     */
    public function __invoke(ContainerInterface $container): AccessLogMiddleware
    {
        $logger = $container->get(LoggerInterface::class);

        return new AccessLogMiddleware($logger);
    }
}
