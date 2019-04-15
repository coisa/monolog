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

use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class StreamHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return StreamHandler
     */
    public function __invoke(ContainerInterface $container): StreamHandler
    {
        $stream = 'php://stdout';

        if ($container->has('config')) {
            $config = $container->get('config');

            if (!\array_key_exists(self::class, $config)) {
                return new StreamHandler($stream);
            }

            if (!\is_string($config[self::class])
                && !\is_resource($config[self::class])
            ) {
                return new StreamHandler($stream);
            }

            $stream = $config[self::class];
        }

        return new StreamHandler($stream);
    }
}
