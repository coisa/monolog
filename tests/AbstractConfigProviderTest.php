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

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractConfigProviderTest
 *
 * @package CoiSA\Monolog\Test
 */
abstract class AbstractConfigProviderTest extends TestCase
{
    public function testInvokeWillReturnArrayWithConfigs()
    {
        $config = $this->getConfig();
        $this->assertIsArray($config);

        return $config;
    }

    /**
     * @depends testInvokeWillReturnArrayWithConfigs
     */
    public function testInvokeWillReturnArrayWithDependenciesIndex(array $config)
    {
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertIsArray($config['dependencies']);

        return $config['dependencies'];
    }

    public function testConfigProviderHasMethodGetDependencies(): void
    {
        $this->assertTrue(\method_exists($this->getConfigProvider(), 'getDependencies'));
        $this->assertIsArray($this->getConfigProvider()->getDependencies());
    }

    /**
     * @depends testInvokeWillReturnArrayWithDependenciesIndex
     * @depends testConfigProviderHasMethodGetDependencies
     */
    public function testGetterDependenciesIsSame(array $dependencies): void
    {
        $this->assertSame($dependencies, $this->getConfigProvider()->getDependencies());
    }

    abstract protected function getConfigProvider(): callable;

    protected function getConfig(): array
    {
        return ($this->getConfigProvider())();
    }
}
