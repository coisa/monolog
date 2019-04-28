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

use Monolog\Handler\RedisHandler;
use Psr\Container\ContainerInterface;

/**
 * Class RedisHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class RedisHandlerFactory
{
    /**
     * @var string
     */
    const DEFAULT_KEY = 'monolog';

    /**
     * @param ContainerInterface $container
     *
     * @throws \InvalidArgumentException
     *
     * @return RedisHandler
     */
    public function __invoke(ContainerInterface $container): RedisHandler
    {
        $key = $this->getKey($container);

        return new RedisHandler(
            $container->get(\Redis::class),
            $key
        );
    }

    /**
     * @param ContainerInterface $container
     * @param string             $default
     *
     * @return string
     */
    private function getKey(ContainerInterface $container, string $default = self::DEFAULT_KEY): string
    {
        if (!$container->has('config')) {
            return $default;
        }

        $config = $container->get('config');

        if (!\array_key_exists(self::class, $config)) {
            return $default;
        }

        $value = $config[self::class];

        return \is_string($value) ? $value : $default;
    }
}
