<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
use Monolog\Handler;

/**
 * Class StrategiesConfigProvider
 *
 * @package CoiSA\Monolog\Container\ConfigProvider
 */
class StrategiesConfigProvider
{
    /**
     * Return dependency mappings for logging strategies
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
     * Return dependency mappings for logging strategies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories' => [
                Handler\GroupHandler::class          => Factory\GroupHandlerFactory::class,
                Handler\FingersCrossedHandler::class => Factory\FingersCrossedHandlerFactory::class,
                Handler\BufferHandler::class         => Factory\BufferHandlerFactory::class,
                Handler\DeduplicationHandler::class  => Factory\DeduplicationHandlerFactory::class,
            ]
        ];
    }
}
