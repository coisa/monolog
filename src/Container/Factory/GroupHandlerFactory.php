<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\ConfigProvider;
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
     * Returns the handlers collection to group in a single GroupHandler
     *
     * @param ContainerInterface $container
     *
     * @return array
     */
    private function getHandlers(ContainerInterface $container): array
    {
        try {
            $config = $container->get(ConfigProvider::class);
        } catch (ContainerExceptionInterface $exception) {
            return [NullHandler::class];
        }

        $handlers = [];

        foreach ($config->getHandlers() as $handler) {
            $handler = $this->getHandler($container, $handler);

            if ($handler instanceof NullHandler) {
                continue;
            }

            $handlers[] = $handler;
        }

        return $handlers;
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
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        if (!$handler instanceof HandlerInterface) {
            return new NullHandler();
        }

        return $handler;
    }
}
