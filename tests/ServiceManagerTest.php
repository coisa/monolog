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
use Psr\Container\ContainerInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ServiceManagerTest
 *
 * @package CoiSA\Monolog\Test
 */
final class ServiceManagerTest extends AbstractContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $config = $this->getConfig();

        $container = new ServiceManager($config['dependencies']);
        $container->setService('config', $config);

        return $container;
    }

    protected function getConfig(): array
    {
        return (new ConfigProvider())();
    }
}
