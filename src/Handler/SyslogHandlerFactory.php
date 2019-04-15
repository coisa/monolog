<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\SyslogHandler;
use Psr\Container\ContainerInterface;

/**
 * Class SyslogHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
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
        return new SyslogHandler('monolog');
    }
}
