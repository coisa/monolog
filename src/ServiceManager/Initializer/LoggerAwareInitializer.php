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

namespace CoiSA\Monolog\ServiceManager\Initializer;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class LoggerAwareInitializer
 *
 * @package CoiSA\Monolog\ServiceManager\Initializer
 */
final class LoggerAwareInitializer implements InitializerInterface
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
