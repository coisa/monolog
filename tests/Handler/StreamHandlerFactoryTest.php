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

namespace CoiSA\Monolog\Test\Handler;

use CoiSA\Monolog\Handler\StreamHandlerFactory;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class StreamHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var StreamHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->factory = new StreamHandlerFactory();
    }

    public function testFactoryWihoutConfigReturnStreamHandlerDefaultPath(): void
    {
        $this->container->has('config')->willReturn(false);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertEquals(StreamHandlerFactory::DEFAULT_PATH, $handler->getUrl());
    }

    public function testFactoryWithEmptyConfigReturnRepositoryWithCurrentWorkingDir(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertEquals(StreamHandlerFactory::DEFAULT_PATH, $handler->getUrl());
    }

    public function testFactoryWithInvalidConfigReturnRepositoryWithCurrentWorkingDir(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            StreamHandlerFactory::class => new \stdClass()
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertEquals(StreamHandlerFactory::DEFAULT_PATH, $handler->getUrl());
    }

    public function testFactoryWithConfigReturnRepositoryWithGivenPath(): void
    {
        $path = \sys_get_temp_dir();

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            StreamHandlerFactory::class => $path
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertEquals($path, $handler->getUrl());
    }

    public function testFactoryWithConfigReturnRepositoryWithGivenStream(): void
    {
        $resource = \fopen(\sys_get_temp_dir(), 'rb');

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            StreamHandlerFactory::class => $resource
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertEquals($resource, $handler->getStream());
    }
}
