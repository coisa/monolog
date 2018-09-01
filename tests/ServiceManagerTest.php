<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Test;

use CoiSA\Monolog\ConfigProvider;
use Monolog\Handler\RavenHandler;
use Monolog\Handler\RedisHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ServiceManagerTest
 *
 * @package CoiSA\Monolog\Test
 */
class ServiceManagerTest extends TestCase
{
    /**
     * @dataProvider provideServiceNames
     */
    public function testCanCreateService(string $serviceName)
    {
        $config = new ConfigProvider();
        $dependencies = $config->getDependencies();

        $serviceManager = new ServiceManager($dependencies);

        $object = $serviceManager->get($serviceName);

        $this->assertInstanceOf($serviceName, $object);
    }

    /**
     * Provide service names for tests
     *
     * @return array
     */
    public function provideServiceNames()
    {
        $config = new ConfigProvider();
        $dependencies = $config->getDependencies();

        $services = array_keys(array_merge(...array_values($dependencies)));

        $services = array_filter($services, function ($value) {
            $ignore = [
                'logger',
                LoggerInterface::class,
                RedisHandler::class,
                RavenHandler::class,
            ];

            return false === \in_array($value, $ignore);
        }, ARRAY_FILTER_USE_BOTH);

        return array_map(function ($item) {
            return [$item];
        }, $services);
    }
}
