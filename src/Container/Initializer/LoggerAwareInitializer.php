<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Container\Initializer;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class LoggerAwareInitializer
 *
 * @package CoiSA\Monolog\Container\Initializer
 */
class LoggerAwareInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     * @param mixed              $instance
     */
    public function __invoke(ContainerInterface $container, $instance): void
    {
        if ($instance instanceof LoggerAwareInterface) {
            try {
                $logger = $container->get(LoggerInterface::class);
                $instance->setLogger($logger);
            } catch (ContainerExceptionInterface $exception) {
                // noop
            }
        }
    }
}
