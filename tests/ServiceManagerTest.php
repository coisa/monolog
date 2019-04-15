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
class ServiceManagerTest extends ContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $configProvider = new ConfigProvider();

        return new ServiceManager($configProvider->getDependencies());
    }
}
