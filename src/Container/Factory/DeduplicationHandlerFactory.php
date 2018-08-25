<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class DeduplicationHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class DeduplicationHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = $container->get(GroupHandler::class);
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new DeduplicationHandler($handler);
    }
}
