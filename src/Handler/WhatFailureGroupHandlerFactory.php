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

use Monolog\Handler\GroupHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerInterface;

/**
 * Class WhatFailureGroupHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class WhatFailureGroupHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return WhatFailureGroupHandler
     */
    public function __invoke(ContainerInterface $container): WhatFailureGroupHandler
    {
        return new WhatFailureGroupHandler([
            $container->get(GroupHandler::class)
        ]);
    }
}
