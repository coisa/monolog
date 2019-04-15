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

use Monolog\Handler\SyslogHandler;
use Psr\Container\ContainerInterface;

/**
 * Class SyslogHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class SyslogHandlerFactory
{
    /**
     * Syslog handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return SyslogHandler
     */
    public function __invoke(ContainerInterface $container): SyslogHandler
    {
        return new SyslogHandler(
            $this->getIdentity($container)
        );
    }

    /**
     * @param ContainerInterface $container
     * @param string             $default
     *
     * @return string
     */
    private function getIdentity(ContainerInterface $container, string $default = 'monolog'): string
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
