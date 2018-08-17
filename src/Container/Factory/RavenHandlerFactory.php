<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\RavenHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Raven_Client;

/**
 * Class RavenHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class RavenHandlerFactory
{
    /**
     * Raven handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $client = $container->get(Raven_Client::class);
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new RavenHandler($client);
    }
}