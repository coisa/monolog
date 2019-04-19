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

namespace CoiSA\Monolog\Log;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;

/**
 * Class LoggerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class LoggerFactory
{
    /**
     * @const string Default logger context name
     */
    const DEFAULT_NAME = 'monolog';

    /**
     * @var string Logger context name
     */
    private $name;

    /**
     * LoggerFactory constructor.
     *
     * @param string $name optional Logger context name
     */
    public function __construct(string $name = self::DEFAULT_NAME)
    {
        $this->name = $name;
    }

    /**
     * Logger service factory
     *
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container): Logger
    {
        $logger = new Logger($this->name);

        $this->setHandler($logger, $container);
        $this->setProcessor($logger, $container);

        return $logger;
    }

    /**
     * @param Logger             $logger
     * @param ContainerInterface $container
     */
    private function setHandler(Logger $logger, ContainerInterface $container): void
    {
        if (!$container->has(ProcessorInterface::class)) {
            return;
        }

        $logger->pushHandler(
            $container->get(HandlerInterface::class)
        );
    }

    /**
     * @param Logger             $logger
     * @param ContainerInterface $container
     */
    private function setProcessor(Logger $logger, ContainerInterface $container): void
    {
        if (!$container->has(HandlerInterface::class)) {
            return;
        }

        $logger->pushProcessor(
            $container->get(ProcessorInterface::class)
        );
    }
}
