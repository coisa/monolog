<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Middleware;

use CoiSA\Monolog\Middleware\AccessLogMiddleware;
use CoiSA\Monolog\Middleware\ConfigProvider;
use CoiSA\Monolog\Middleware\LoggerAwareMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\Monolog\Test\Middleware
 */
final class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    private $configProvider;

    public function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function shouldProvideThisFactories(): array
    {
        return [
            [AccessLogMiddleware::class],
            [LoggerAwareMiddleware::class],
        ];
    }

    public function testMethodInvokeReturnArray()
    {
        $config = ($this->configProvider)();
        $this->assertIsArray($config);

        return $config;
    }

    /**
     * @depends testMethodInvokeReturnArray
     */
    public function testMethodInvokeReturnArrayWithDependencies(array $config)
    {
        $index = 'dependencies';

        $this->assertArrayHasKey($index, $config);
        $this->assertIsArray($config[$index]);

        return $config[$index];
    }

    /**
     * @depends testMethodInvokeReturnArrayWithDependencies
     */
    public function testConfigHasFactoriesDefined(array $dependencies)
    {
        $index = 'factories';

        $this->assertArrayHasKey($index, $dependencies);
        $this->assertIsArray($dependencies[$index]);

        return $dependencies[$index];
    }

    /**
     * @depends testConfigHasFactoriesDefined
     * @dataProvider shouldProvideThisFactories
     */
    public function testConfigHasFactoryForService(string $namespace, array $factories): void
    {
        $this->assertArrayHasKey($namespace, $factories);
    }

    public function testConfigProviderHasGetDependenciesMethod(): void
    {
        $this->assertTrue(\method_exists($this->configProvider, 'getDependencies'));
    }

    /**
     * @depends testConfigProviderHasGetDependenciesMethod
     */
    public function testConfigProviderGetDependenciesMethodReturnArray(): array
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertIsArray($dependencies);

        return $dependencies;
    }

    /**
     * @depends testConfigProviderGetDependenciesMethodReturnArray
     */
    public function textConfigProviderGetDependenciesMethodReturnSameAsInvoke(array $dependencies): void
    {
        $this->assertSame(($this->configProvider)()['dependencies'], $dependencies);
    }
}
