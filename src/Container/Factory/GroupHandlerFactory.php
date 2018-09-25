<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * @package CoiSA\Monolog\Container\Factory
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

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class GroupHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class GroupHandlerFactory
{
    /**
     * Group handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return GroupHandler
     */
    public function __invoke(ContainerInterface $container): GroupHandler
    {
        $handlers = $this->getHandlers($container);

        return new WhatFailureGroupHandler($handlers);
    }

    /**
     * Returns the handlers collection to group into a single GroupHandler
     *
     * @param ContainerInterface $container
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

        foreach ($config[GroupHandler::class] as $handler) {
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
        } catch (ContainerExceptionInterface $exception) {
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
}
