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

use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class FingersCrossedHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class FingersCrossedHandlerFactory
{
    /**
     * Fingers crossed handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return FingersCrossedHandler
     */
    public function __invoke(ContainerInterface $container): FingersCrossedHandler
    {
        return new FingersCrossedHandler(
            $container->get(WhatFailureGroupHandler::class),
            Logger::ERROR
        );
    }
}
