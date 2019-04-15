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
use CoiSA\Monolog\Middleware\AccessLogMiddlewareFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class AccessLogMiddlewareFactoryTest
 *
 * @package CoiSA\Monolog\Test\Middleware
 */
final class AccessLogMiddlewareFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    private $logger;

    /** @var AccessLogMiddlewareFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->factory = new AccessLogMiddlewareFactory();

        $this->container->get(LoggerInterface::class)->will([$this->logger, 'reveal']);
    }

    public function testFactoryRaiseExceptionWhenLoggerNotFound(): void
    {
        $this->container->get(LoggerInterface::class)->willThrow(new ServiceNotFoundException());

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotLoggerInterface(): void
    {
        $this->container->get(LoggerInterface::class)->willReturn(new \stdClass());

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithLoggerReturnAccessLogMiddleware(): void
    {
        $processor = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(MiddlewareInterface::class, $processor);
        $this->assertInstanceOf(AccessLogMiddleware::class, $processor);
    }
}
