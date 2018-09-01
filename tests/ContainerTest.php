<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Test
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Test;

use CoiSA\Monolog\ConfigProvider;
use Monolog\Handler\RavenHandler;
use Monolog\Handler\RedisHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ContainerTest
 *
 * @package CoiSA\Monolog\Test
 */
abstract class ContainerTest extends TestCase
{
    /**
     * @return ContainerInterface
     */
    abstract public function getContainer(): ContainerInterface;

    /**
     * Test if a container can create a service with given service name
     *
     * @param string $serviceName Service name to locate
     * @param string $instanceOf Instance name to compare
     *
     * @dataProvider provideServiceNames
     */
    public function testCanCreateService(string $serviceName, string $instanceOf)
    {
        $object = $this->getContainer()->get($serviceName);

        $this->assertInstanceOf($instanceOf, $object);
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

        $services = array_filter($services, function ($serviceName) {
            $notStrictDependecies = [
                RedisHandler::class,
                RavenHandler::class,
            ];

            return false === \in_array($serviceName, $notStrictDependecies);
        }, ARRAY_FILTER_USE_BOTH);

        return array_map(function ($serviceName) {
            $instanceOf = $serviceName === 'logger' ?
                LoggerInterface::class :
                $serviceName
            ;

            return [
                $serviceName,
                $instanceOf
            ];
        }, $services);
    }
}
