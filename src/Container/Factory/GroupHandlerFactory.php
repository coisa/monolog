<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\Container\ConfigProvider\HandlersConfigProvider;
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
    public function __invoke(ContainerInterface $container) : GroupHandler
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
            $configProvider = $container->get(HandlersConfigProvider::class);
        } catch (ContainerExceptionInterface $exception) {
            return $handlers;
        }

        $dependencies = array_merge(...array_values($configProvider->getDependencies()));

        foreach (array_keys($dependencies) as $handler) {
            $handlers[] = $this->getHandler($container, $handler);
        }

        return array_filter(
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
     * @param string $className
     *
     * @return HandlerInterface
     */
    private function getHandler(ContainerInterface $container, string $className) : HandlerInterface
    {
        try {
            $handler = $container->get($className);

            if (!$handler instanceof HandlerInterface) {
                return $container->get(NullHandler::class);
            }
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return $handler;
    }
}
