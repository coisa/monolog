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

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Container\Factory;
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
            'aliases'      => [
                'logger'               => Logger::class,
                'monolog'              => Logger::class,
                LoggerInterface::class => Logger::class,
            ],
            'factories'    => [
                Logger::class => Factory\LoggerFactory::class,
            ],
        ];
    }
}
