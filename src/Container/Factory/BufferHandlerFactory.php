<?php
/***
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class BufferHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class BufferHandlerFactory
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

        return new BufferHandler($handler);
    }
}
