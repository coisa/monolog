<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
use CoiSA\Monolog\Http\Middleware\AccessLogMiddleware;
use CoiSA\Monolog\Http\Middleware\LoggerAwareMiddleware;

/**
 * Class MiddlewareConfigProvider
 *
 * @package CoiSA\Monolog\Container\ConfigProvider
 */
final class MiddlewareConfigProvider
{
    /**
     * Return config mappings for the Logger
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Return dependency mappings for the Logger
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories' => [
                AccessLogMiddleware::class   => Factory\AccessLogMiddlewareFactory::class,
                LoggerAwareMiddleware::class => Factory\LoggerAwareMiddlewareFactory::class,
            ],
        ];
    }
}
