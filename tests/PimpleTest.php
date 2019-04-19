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
use CoiSA\Monolog\Container\ServiceProvider\PimpleServiceProvider;
use Pimple\Container;
use Psr\Container\ContainerInterface;

/**
 * Class PimpleTest
 *
 * @package CoiSA\Monolog\Test
 */
final class PimpleTest extends AbstractContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $pimple = new Container();
        $pimple->register(new PimpleServiceProvider());

        return new \Pimple\Psr11\Container($pimple);
    }

    protected function getConfig(): array
    {
        return (new ConfigProvider())();
    }
}
