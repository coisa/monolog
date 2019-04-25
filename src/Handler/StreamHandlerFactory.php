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

use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
final class StreamHandlerFactory
{
    /**
     * @var string
     */
    const DEFAULT_PATH = 'php://stdout';

    /**
     * @param ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return StreamHandler
     */
    public function __invoke(ContainerInterface $container): StreamHandler
    {
        return new StreamHandler(
            $this->getStream($container)
        );
    }

    /**
     * @param ContainerInterface $container
     * @param string             $default
     *
     * @return resource|string
     */
    private function getStream(ContainerInterface $container, string $default = self::DEFAULT_PATH)
    {
        if (!$container->has('config')) {
            return $default;
        }

        $config = $container->get('config');

        if (!\array_key_exists(self::class, $config)) {
            return $default;
        }

        $value = $config[self::class];

        if (\is_string($value) || \is_resource($value)) {
            return $value;
        }

        return $default;
    }
}
