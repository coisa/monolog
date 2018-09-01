<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Test
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
