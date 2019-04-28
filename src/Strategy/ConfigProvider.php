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

namespace CoiSA\Monolog\Strategy;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog\Strategy
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            'aliases' => [
                HandlerInterface::class => StrategyInterface::class
            ],
            'invokables' => [
                NullHandler::class => NullHandler::class
            ],
            'factories' => [
                StrategyInterface::class => StrategyHandlerFactory::class
            ],
        ];
    }
}
