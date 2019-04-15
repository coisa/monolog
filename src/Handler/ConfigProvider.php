<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Handler;

use Monolog\Handler;
use Raven_Client;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog\Handler
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            Handler\GroupHandler::class => \array_keys(\array_merge(...\array_values($this->getDependencies())))
        ];
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            'invokables' => [
                Raven_Client::class => Raven_Client::class
            ],
            'factories' => [
                Handler\StreamHandler::class => StreamHandlerFactory::class,
                Handler\RavenHandler::class  => RavenHandlerFactory::class,
                Handler\SyslogHandler::class => SyslogHandlerFactory::class,
                Handler\RedisHandler::class  => RedisHandlerFactory::class,
            ],
        ];
    }
}
