<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Handler;

use Monolog\Handler\RavenHandler;
use Psr\Container\ContainerInterface;
use Raven_Client;

/**
 * Class RavenHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class RavenHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return RavenHandler
     */
    public function __invoke(ContainerInterface $container): RavenHandler
    {
        return new RavenHandler(
            $container->get(Raven_Client::class)
        );
    }
}
