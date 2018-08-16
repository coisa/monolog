<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\ConfigProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class HandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class HandlerFactory
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
        try {
            $configProvider = $container->get(ConfigProvider::class);
            $strategy = $configProvider->getStrategy();
        } catch (ContainerExceptionInterface $exception) {
            $strategy = null;
        }

        if ($strategy === null) {
            $strategy = NullHandler::class;
        }

        try {
            $handler = $container->get($strategy);

            if (!$handler instanceof HandlerInterface) {
                return $container->get(NullHandler::class);
            }
        } catch (ContainerExceptionInterface $exception) {
            // Prevent application crashes
            return new NullHandler();
        }

        return $handler;
    }
}