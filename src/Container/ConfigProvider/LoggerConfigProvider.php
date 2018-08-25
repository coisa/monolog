<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
use CoiSA\Monolog\Container\Initializer;
use Monolog\Handler;
use Monolog\Logger;
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
            'services'     => [
                __CLASS__ => $this
            ],
            'aliases'      => [
                'logger'               => Logger::class,
                LoggerInterface::class => Logger::class,
            ],
            'factories'    => [
                Logger::class                   => Factory\LoggerFactory::class,
                Handler\HandlerInterface::class => Factory\StrategyHandlerFactory::class,
            ],
            'initializers' => [
                Initializer\LoggerAwareInitializer::class
            ],
        ];
    }
}
