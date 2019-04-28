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
use CoiSA\Monolog\Strategy\StrategyInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\Monolog\Test
 */
final class ConfigProviderTest extends AbstractConfigProviderTest
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
            [\CoiSA\Monolog\Initializer\ConfigProvider::class],
            [\CoiSA\Monolog\Strategy\ConfigProvider::class],
        ];
    }

    public function provideStrategies()
    {
        $reflection = new \ReflectionClass(StrategyInterface::class);
        $constants  = $reflection->getConstants();

        return \array_chunk($constants, 1);
    }

    /**
     * @dataProvider provideStrategies
     */
    public function testAssertSameStrategyGivenInConstructor(string $strategy): void
    {
        $configProvider = new ConfigProvider($strategy);
        $config         = ($configProvider)();

        $this->assertSame($strategy, $config[StrategyInterface::class]);
    }

    /**
     * @dataProvider provideConfigProviderShouldAggregate
     */
    public function testConfigProviderAggregateAllConfigProviders(string $namespace): void
    {
        $object = new $namespace();

        $merged = ArrayUtils::merge(
            $this->getConfig(),
            ($object)(),
            true
        );

        $this->assertSame($merged, $this->getConfig());
    }

    public function testInvokeWillReturnArrayWithStrategy(): void
    {
        $config = $this->getConfig();

        $this->assertArrayHasKey(StrategyInterface::class, $config);
        $this->assertIsString($config[StrategyInterface::class]);
    }

    protected function getConfigProvider(): callable
    {
        return new ConfigProvider();
    }
}
