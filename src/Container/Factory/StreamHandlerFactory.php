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

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class StreamHandlerFactory
{
    /**
     * Stream handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = new StreamHandler('php://stdout', Logger::INFO);
        } catch (\Exception $exception) {
            $handler = new NullHandler();
        }

        return $handler;
    }
}
