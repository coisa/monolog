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

use CoiSA\Monolog\Handler\RedisHandlerFactory;
use Monolog\Handler\RedisHandler;
use PHPUnit\Framework\TestCase;
use Predis\Client;
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

    /** @var ObjectProphecy|\Redis */
    private $redis;

    /** @var \ReflectionProperty */
    private $redisKey;

    /** @var RedisHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->redis     = $this->prophesize(\Redis::class);

        if (!\class_exists(\Redis::class)) {
            $this->redis->willExtend(Client::class);
        }

        $this->factory = new RedisHandlerFactory();

        $this->container->get(\Redis::class)->will([$this->redis, 'reveal']);

        $reflection     = new \ReflectionClass(RedisHandler::class);
        $this->redisKey = $reflection->getProperty('redisKey');
        $this->redisKey->setAccessible(true);
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

    public function testFactoryWithoutConfigReturnRedisHandlerWithDefaultKey(): void
    {
        $this->container->has('config')->willReturn(false);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
        $this->assertEquals(RedisHandlerFactory::DEFAULT_KEY, $this->redisKey->getValue($handler));
    }

    public function testFactoryWithEmptyConfigReturnRedisHandlerWithDefaultKey(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
        $this->assertEquals(RedisHandlerFactory::DEFAULT_KEY, $this->redisKey->getValue($handler));
    }

    public function testFactoryWithInvalidConfigReturnRedisHandlerWithDefaultKey(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            RedisHandlerFactory::class => new \stdClass()
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
        $this->assertEquals(RedisHandlerFactory::DEFAULT_KEY, $this->redisKey->getValue($handler));
    }

    public function testFactoryWithConfigReturnRedisHandlerWithGivenKey(): void
    {
        $key = \uniqid('test', true);

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            RedisHandlerFactory::class => $key
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RedisHandler::class, $handler);
        $this->assertEquals($key, $this->redisKey->getValue($handler));
    }
}
