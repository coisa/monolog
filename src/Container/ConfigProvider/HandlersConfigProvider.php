<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

declare(strict_types=1);

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
            'dependencies' => $this->getDependencies()
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
            'services'   => [
                __CLASS__ => $this
            ],
            'invokables' => [
                Handler\NullHandler::class           => Handler\NullHandler::class,
                Handler\BrowserConsoleHandler::class => Handler\BrowserConsoleHandler::class,
                Handler\ChromePHPHandler::class      => Handler\ChromePHPHandler::class,
                Raven_Client::class                  => Raven_Client::class
            ],
            'factories'  => [
                Handler\StreamHandler::class => Factory\StreamHandlerFactory::class,
                Handler\SyslogHandler::class => Factory\SyslogHandlerFactory::class,
                Handler\RedisHandler::class  => Factory\RedisHandlerFactory::class,
                Handler\RavenHandler::class  => Factory\RavenHandlerFactory::class,
            ],
        ];
    }
}