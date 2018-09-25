<?php
/***
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
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
