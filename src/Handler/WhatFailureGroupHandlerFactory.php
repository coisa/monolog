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

namespace CoiSA\Monolog\Handler;

use CoiSA\Monolog\Strategy\StrategyInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class WhatFailureGroupHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class WhatFailureGroupHandlerFactory
{
    /**
     * Group handler service factory
     *
     * @param ContainerInterface $container
     *
     * @throws \ReflectionException
     *
     * @return WhatFailureGroupHandler
     */
    public function __invoke(ContainerInterface $container): WhatFailureGroupHandler
    {
        $handlers = $this->getHandlers($container);

        return new WhatFailureGroupHandler($handlers);
    }

    /**
     * Returns the handlers collection to group into a single GroupHandler
     *
     * @param ContainerInterface $container
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    private function getHandlers(ContainerInterface $container): array
    {
        $handlers = [
            new NullHandler()
        ];

        try {
            $config = $container->get('config');
        } catch (ContainerExceptionInterface $exception) {
            return $handlers;
        }

        $dependencies = $this->getCandidates($config);

        foreach ($dependencies as $handler) {
            if (!\is_string($handler)) {
                continue;
            }

            if (!$this->isHandler($handler)) {
                continue;
            }

            $handlers[] = $this->getHandler($container, $handler);
        }

        return \array_filter(
            $handlers,
            function ($handler) {
                return !($handler instanceof NullHandler);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Try to get a single handler based on the given class name
     *
     * @param ContainerInterface $container
     * @param string             $className
     *
     * @return HandlerInterface
     */
    private function getHandler(ContainerInterface $container, string $className): HandlerInterface
    {
        try {
            $handler = $container->get($className);
        } catch (\Throwable $exception) {
            return new NullHandler();
        }

        return $handler;
    }

    /**
     * Check if a given class name belongs to a handler
     *
     * @param string $className Class name to verify if is a handler
     *
     * @return bool
     */
    private function isHandler(string $className): bool
    {
        $implements = @\class_implements($className);
        if (false === $implements) {
            return false;
        }

        if (false === \in_array(HandlerInterface::class, $implements)) {
            return false;
        }

        return true;
    }

    /**
     * @param $config
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    private function getCandidates($config): array
    {
        $dependencies = \array_keys(\array_merge(
            $config['dependencies']['invokables'],
            $config['dependencies']['factories']
        ));

        $interface = new \ReflectionClass(StrategyInterface::class);
        $constants = $interface->getConstants();

        $dependencies = \array_diff($dependencies, $constants);

        return $dependencies;
    }
}
