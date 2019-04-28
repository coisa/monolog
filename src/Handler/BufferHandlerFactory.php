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

namespace CoiSA\Monolog\Handler;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerInterface;

/**
 * Class BufferHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class BufferHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return BufferHandler
     */
    public function __invoke(ContainerInterface $container): BufferHandler
    {
        return new BufferHandler(
            $container->get(WhatFailureGroupHandler::class)
        );
    }
}
