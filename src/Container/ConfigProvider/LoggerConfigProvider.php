<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
use Monolog\Logger;
use Monolog\Handler;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerConfigProvider
 *
 * @package CoiSA\Monolog\Container\ConfigProvider
 */
class LoggerConfigProvider
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
            'services' => [
                LoggerConfigProvider::class => $this
            ],
            'aliases'   => [
                'logger'               => Logger::class,
                LoggerInterface::class => Logger::class,
            ],
            'factories' => [
                Logger::class                   => Factory\LoggerFactory::class,
                Handler\HandlerInterface::class => Factory\HandlerFactory::class,
            ],
        ];
    }
}