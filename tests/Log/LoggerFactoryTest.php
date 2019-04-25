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

namespace CoiSA\Monolog\Test\Middleware;

use CoiSA\Monolog\Log\LoggerFactory;
use Monolog\Handler\HandlerInterface;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Middleware
 */
final class LoggerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var HandlerInterface|ObjectProphecy */
    private $handler;

    /** @var ObjectProphecy|ProcessorInterface */
    private $processor;

    /** @var LoggerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->logger    = $this->prophesize(LoggerInterface::class);
        $this->handler   = $this->prophesize(HandlerInterface::class);
        $this->processor = $this->prophesize(ProcessorInterface::class);

        $this->factory = new LoggerFactory();

        $this->container->get(LoggerInterface::class)->will([$this->logger, 'reveal']);

        $this->container->has(HandlerInterface::class)->willReturn(true);
        $this->container->get(HandlerInterface::class)->will([$this->handler, 'reveal']);

        $this->container->has(ProcessorInterface::class)->willReturn(true);
        $this->container->get(ProcessorInterface::class)->will([$this->processor, 'reveal']);
    }

    public function testFactoryWithHandlerWillReturnLoggerWithPushedHandler(): void
    {
        $logger = ($this->factory)($this->container->reveal());

        $this->assertContains($this->handler->reveal(), $logger->getHandlers());
    }
}
