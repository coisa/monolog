<?php

/**
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareMiddlewareFactory
 *
 * @package CoiSA\Monolog\Middleware
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
