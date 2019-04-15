<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Handler;

use CoiSA\Monolog\Handler\RedisHandlerFactory;
use CoiSA\Monolog\Handler\StreamHandlerFactory;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class RedisHandlerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class RedisHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var \Redis|ObjectProphecy */
    private $redis;

    /** @var RedisHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->redis = $this->prophesize(\Redis::class);

        $this->factory = new RedisHandlerFactory();

        $this->container->get(\Redis::class)->will([$this->redis, 'reveal']);
    }

    public function testFactoryRaiseExceptionWhenRedisNotFound(): void
    {
        $this->container->get(\Redis::class)->willThrow(new ServiceNotFoundException());
        $this->container->has('config')->willReturn(false);

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotRedis(): void
    {
        $this->container->get(\Redis::class)->willReturn(new \stdClass());
        $this->container->has('config')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWihoutConfigReturnRedisHandler(): void
    {
        $this->markTestIncomplete();

        $this->container->has('config')->willReturn(false);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }

    public function testFactoryWithEmptyConfigReturnRedisHandler(): void
    {
        $this->markTestIncomplete();

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }

    public function testFactoryWithInvalidConfigReturnRedisHandler(): void
    {
        $this->markTestIncomplete();

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            RedisHandlerFactory::class => new \stdClass()
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }

    public function testFactoryWithConfigReturnRedisHandlerWithGivenKey(): void
    {
        $this->markTestIncomplete();

        $key = uniqid('test', true);

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            RedisHandlerFactory::class => $key
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }
}
