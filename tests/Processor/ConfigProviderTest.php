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
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Processor;

use CoiSA\Monolog\Processor\ConfigProvider;
use CoiSA\Monolog\Processor\GitProcessor;
use Monolog\Processor;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\Monolog\Test\Processor
 */
final class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    private $configProvider;

    public function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function shouldProvideThisInvokables(): array
    {
        return [
            [Processor\PsrLogMessageProcessor::class],
            [Processor\UidProcessor::class],
            [Processor\ProcessIdProcessor::class],
            [Processor\MemoryUsageProcessor::class],
            [Processor\MemoryPeakUsageProcessor::class],
            [Processor\IntrospectionProcessor::class],
            [Processor\WebProcessor::class],
            [Processor\TagProcessor::class],
        ];
    }

    public function shouldProvideThisFactories(): array
    {
        return [
            [GitProcessor::class],
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
    public function testMethodInvokeReturnArrayWithClassConfig(array $config)
    {
        $index = \get_class($this->configProvider);

        $this->assertArrayHasKey($index, $config);
        $this->assertIsArray($config[$index]);

        return $config[$index];
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
    public function testConfigHasInvokablesDefined(array $dependencies)
    {
        $index = 'invokables';

        $this->assertArrayHasKey($index, $dependencies);
        $this->assertIsArray($dependencies[$index]);

        return $dependencies[$index];
    }

    /**
     * @depends testConfigHasInvokablesDefined
     * @dataProvider shouldProvideThisInvokables
     */
    public function testConfigHasInvokableForService(string $namespace, array $factories): void
    {
        $this->assertArrayHasKey($namespace, $factories);
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
