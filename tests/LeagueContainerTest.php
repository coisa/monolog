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

namespace CoiSA\Monolog\Test;

use CoiSA\Monolog\ConfigProvider;
use CoiSA\Monolog\Container\ServiceProvider\LeagueServiceProvider;
use League\Container\Container;
use Psr\Container\ContainerInterface;

/**
 * Class LeagueContainerTest
 *
 * @package CoiSA\Monolog\Test
 */
final class LeagueContainerTest extends AbstractContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $container = new Container();
        $container->addServiceProvider(new LeagueServiceProvider());

        return $container;
    }

    protected function getConfig(): array
    {
        return (new ConfigProvider())();
    }
}
