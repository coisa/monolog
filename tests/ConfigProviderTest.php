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
use CoiSA\Monolog\Container\ConfigProvider\LoggerConfigProvider;
use CoiSA\Monolog\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\Monolog\Test
 */
final class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    private $configProvider;

    public function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function provideConfigProviderShouldAggregate()
    {
        return [
            [\CoiSA\Monolog\Git\ConfigProvider::class],
            [\CoiSA\Monolog\Handler\ConfigProvider::class],
            [\CoiSA\Monolog\Middleware\ConfigProvider::class],
            [\CoiSA\Monolog\Processor\ConfigProvider::class],

            [LoggerConfigProvider::class],
        ];
    }

    public function provideConfigProviderConstants()
    {
        $reflection = new \ReflectionClass(StrategyInterface::class);
        $constants  = $reflection->getConstants();

        return \array_chunk($constants, 1);
    }

    /**
     * @dataProvider provideConfigProviderConstants
     */
    public function testAssertSameStrategyGivenInConstructor(string $strategy): void
    {
        $this->configProvider = new ConfigProvider($strategy);

        $config = ($this->configProvider)();

        $this->assertSame($strategy, $config[StrategyInterface::class]);
    }

    public function testMethodInvokeReturnArray()
    {
        $config = ($this->configProvider)();
        $this->assertIsArray($config);

        return $config;
    }

    /**
     * @dataProvider provideConfigProviderShouldAggregate
     */
    public function testConfigProviderAggregateAllConfigProviders(string $namespace): void
    {
        $object = new $namespace();

        $provided = ($this->configProvider)();
        $config   = ($object)();

        $merged = ArrayUtils::merge($provided, $config, true);

        $this->assertSame($merged, ($this->configProvider)());
    }

    /**
     * @depends testMethodInvokeReturnArray
     */
    public function testMethodInvokeReturnArrayWithStrategy(array $config): void
    {
        $this->assertArrayHasKey(StrategyInterface::class, $config);
        $this->assertIsString($config[StrategyInterface::class]);
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
