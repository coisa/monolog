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

use CoiSA\Monolog\Handler\RavenHandlerFactory;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RavenHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Raven_Client;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class RavenHandlerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class RavenHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|Raven_Client */
    private $ravenClient;

    /** @var RavenHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container   = $this->prophesize(ContainerInterface::class);
        $this->ravenClient = $this->prophesize(Raven_Client::class);

        $this->factory = new RavenHandlerFactory();

        $this->container->get(Raven_Client::class)->will([$this->ravenClient, 'reveal']);
    }

    public function testFactoryRaiseExceptionWhenRavenClientNotFound(): void
    {
        $this->container->get(Raven_Client::class)->willThrow(new ServiceNotFoundException());

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotLoggerInterface(): void
    {
        $this->container->get(Raven_Client::class)->willReturn(new \stdClass());

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithRavenClientReturnRavenHandler(): void
    {
        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
        $this->assertInstanceOf(RavenHandler::class, $handler);
    }
}
