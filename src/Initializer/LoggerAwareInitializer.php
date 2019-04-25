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

namespace CoiSA\Monolog\Initializer;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareInitializer
 *
 * @package CoiSA\Monolog\Initializer
 */
final class LoggerAwareInitializer
{
    /**
     * @param ContainerInterface $container
     * @param mixed              $instance
     */
    public function __invoke(ContainerInterface $container, $instance): void
    {
        if (!$instance instanceof LoggerAwareInterface) {
            return;
        }

        $instance->setLogger(
            $container->get(LoggerInterface::class)
        );
    }
}
