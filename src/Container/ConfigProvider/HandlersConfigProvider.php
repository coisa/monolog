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
use Monolog\Handler;
use Raven_Client;

/**
 * Class HandlersConfigProvider
 *
 * @package CoiSA\Monolog\Container\ConfigProvider
 */
class HandlersConfigProvider
{
    /**
     * Return config mappings for handlers
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies'              => $this->getDependencies(),
            Handler\GroupHandler::class => $this->getHandlers(),
        ];
    }

    /**
     * Return dependency mappings for handlers
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories'  => [
                Handler\RedisHandler::class  => Factory\RedisHandlerFactory::class,
            ],
        ];
    }

    /**
     * Provide handlers to put into GroupHandler
     *
     * @return array
     */
    public function getHandlers(): array
    {
        return \array_keys(\array_merge(...\array_values($this->getDependencies())));
    }
}
