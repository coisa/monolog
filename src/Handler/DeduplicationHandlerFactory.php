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
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Handler;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerInterface;

/**
 * Class DeduplicationHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class DeduplicationHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return DeduplicationHandler
     */
    public function __invoke(ContainerInterface $container): DeduplicationHandler
    {
        return new DeduplicationHandler(
            $container->get(WhatFailureGroupHandler::class)
        );
    }
}
