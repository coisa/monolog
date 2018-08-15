<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
use Monolog\Handler;

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
            'services' => [
                HandlersConfigProvider::class => $this
            ],
            'invokables' => [
                Handler\NullHandler::class           => Handler\NullHandler::class,
                Handler\BrowserConsoleHandler::class => Handler\BrowserConsoleHandler::class,
                Handler\ChromePHPHandler::class      => Handler\ChromePHPHandler::class,
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