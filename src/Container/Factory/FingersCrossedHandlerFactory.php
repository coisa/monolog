<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FingersCrossedHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class FingersCrossedHandlerFactory
{
    /**
     * Fingers crossed handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = $container->get(GroupHandler::class);
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new FingersCrossedHandler($handler, Logger::ERROR);
    }
}
