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
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Psr\Container\ContainerInterface;

/**
 * Class GroupHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class GroupHandlerFactory
{
    /**
     * Group handler service factory
     *
     * @param ContainerInterface $container
     *
     * @throws \ReflectionException
     *
     * @return GroupHandler
     */
    public function __invoke(ContainerInterface $container): GroupHandler
    {
        $handlers = $this->getContainerHandlers($container);

        return new GroupHandler($handlers);
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
    private function getContainerHandlers(ContainerInterface $container): array
    {
        $handlers = [];

        $config       = $container->get('config');
        $dependencies = $this->getConfigDependencyHandlerClassNames($config);

        foreach ($dependencies as $handler) {
            $handler = $this->getContainerHandler($container, $handler);

            if (!$handler instanceof HandlerInterface) {
                continue;
            }

            $handlers[] = $handler;
        }

        return \array_filter($handlers);
    }

    /**
     * Try to get a single handler based on the given class name
     *
     * @param ContainerInterface $container
     * @param string             $className
     *
     * @return HandlerInterface
     */
    private function getContainerHandler(ContainerInterface $container, string $className): ?HandlerInterface
    {
        try {
            return $container->get($className);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * @param $config
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    private function getConfigDependencyHandlerClassNames($config): array
    {
        $dependencies = \array_keys(\array_merge(...\array_values($config['dependencies'])));
        $dependencies = \array_filter($dependencies, function ($handler) {
            $implements = @\class_implements($handler);

            return \is_array($implements) && \in_array(HandlerInterface::class, $implements);
        });

        $ignoreHandlers = $this->getStrategyHandlerClassNames();
        $dependencies = \array_diff($dependencies, $ignoreHandlers);

        return $dependencies;
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    private function getStrategyHandlerClassNames(): array
    {
        $reflection = new \ReflectionClass(StrategyInterface::class);

        $ignoreHandlers   = $reflection->getConstants();
        $ignoreHandlers[] = GroupHandler::class;

        return $ignoreHandlers;
    }
}
