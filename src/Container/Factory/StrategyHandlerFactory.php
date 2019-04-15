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

use CoiSA\Monolog\ConfigProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class StrategyHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class StrategyHandlerFactory
{
    /**
     * Default handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        $strategy = $this->getStrategy($container);

        return $this->getHandler($container, $strategy);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return string
     */
    private function getStrategy(ContainerInterface $container): string
    {
        try {
            $config   = $container->get('config');
            $strategy = $config[ConfigProvider::class]['strategy'] ?? ConfigProvider::LAZY;
        } catch (ContainerExceptionInterface $exception) {
            $strategy = null;
        }

        if ($strategy === null) {
            $strategy = NullHandler::class;
        }

        return $strategy;
    }

    /**
     * @param ContainerInterface $container
     * @param $strategy
     *
     * @return HandlerInterface
     */
    private function getHandler(ContainerInterface $container, $strategy): HandlerInterface
    {
        try {
            $handler = $container->get($strategy);
        } catch (ContainerExceptionInterface $exception) {
            $handler = new NullHandler();
        }

        return $handler;
    }
}
