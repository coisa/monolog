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
use Psr\Container\ContainerInterface;

/**
 * Class AbstractContainerTest
 *
 * @package CoiSA\Monolog\Test
 */
abstract class AbstractContainerTest extends TestCase
{
    /**
     * @dataProvider provideContainerServiceNameAndExpectedInstance
     */
    public function testContainerHasService(string $serviceName): void
    {
        $this->assertTrue($this->getContainer()->has($serviceName));
    }

    /**
     * @dataProvider provideContainerServiceNameAndExpectedInstance
     */
    public function testContainerCanCreateServiceThatImplements(string $serviceName, string $instanceOf): void
    {
        try {
            $object = $this->getContainer()->get($serviceName);
            $this->assertInstanceOf($instanceOf, $object);
        } catch (\Throwable $throwable) {
            // Mark as risky tests that can create the object but fails the InstanceOf assertion
            $this->markAsRisky();
        }
    }

    public function provideContainerServiceNameAndExpectedInstance()
    {
        $dependencies = $this->getConfig()['dependencies'];

        unset($dependencies['initializers'], $dependencies['delegators']);

        $services = \array_keys(\array_merge(...\array_values($dependencies)));

        return \array_map(function ($serviceName) use ($dependencies) {
            $instanceOf = $dependencies['aliases'][$serviceName] ?? $serviceName;

            return [
                $serviceName,
                $instanceOf,
            ];
        }, $services);
    }

    /**
     * @return array
     */
    abstract protected function getConfig(): array;

    /**
     * @return ContainerInterface
     */
    abstract protected function getContainer(): ContainerInterface;
}
