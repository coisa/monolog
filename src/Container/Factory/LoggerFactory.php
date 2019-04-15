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

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\Processor\ConfigProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class LoggerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class LoggerFactory
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

        $handler = $this->getHandler($container);
        $logger->pushHandler($handler);

        $this->pushProcessors($container, $logger);

        return $logger;
    }

    /**
     * Set logger processors
     *
     * @param ContainerInterface $container
     * @param Logger             $logger
     */
    private function pushProcessors(ContainerInterface $container, Logger $logger): void
    {
        try {
            $config = $container->get('config');
        } catch (ContainerExceptionInterface $exception) {
            return;
        }

        foreach ($config[ConfigProvider::class] as $processorClass) {
            if ($processorClass === ConfigProvider::class) {
                continue;
            }

            try {
                $processor = $container->get($processorClass);
            } catch (\Throwable $exception) {
                continue; // @TODO log exception!?
            }

            $logger->pushProcessor($processor);
        }
    }

    /**
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    private function getHandler(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = $container->get(HandlerInterface::class);
        } catch (ContainerExceptionInterface $exception) {
            $handler = new NullHandler();
        }

        return $handler;
    }
}
