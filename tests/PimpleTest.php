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
use CoiSA\Monolog\ServiceProvider\PimpleServiceProvider;
use CoiSA\Monolog\Strategy\StrategyInterface;
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
     * @var Container
     */
    private $pimple;

    /**
     * @var array
     */
    private $config;

    public function setUp(): void
    {
        $this->config = [
            StrategyInterface::class => null,
        ];

        $this->pimple = new Container();

        $this->pimple->offsetSet('config', $this->config);
        $this->pimple->register(new PimpleServiceProvider());
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return new \Pimple\Psr11\Container($this->pimple);
    }

    public function testRegisterWillMergeConfigs(): void
    {
        $config = $this->pimple->offsetGet('config');

        $this->assertEquals($this->config, \array_intersect_assoc($this->config, $config));
    }

    protected function getConfig(): array
    {
        return (new ConfigProvider())();
    }
}
