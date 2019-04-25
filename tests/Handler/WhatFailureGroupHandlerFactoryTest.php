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

use CoiSA\Monolog\Handler\WhatFailureGroupHandlerFactory;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\WhatFailureGroupHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class WhatFailureGroupHandlerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class WhatFailureGroupHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|WhatFailureGroupHandler */
    private $handler;

    /** @var WhatFailureGroupHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->handler   = $this->prophesize(GroupHandler::class);

        $this->factory = new WhatFailureGroupHandlerFactory();

        $this->container->get(GroupHandler::class)->will([$this->handler, 'reveal']);
    }

    public function testFactoryRaiseExceptionWhenGroupHandlerNotFound(): void
    {
        $this->container->get(GroupHandler::class)->willThrow(ServiceNotFoundException::class);

        $this->expectException(ContainerExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithGroupHandlerReturnWhatFailureGroupHandler(): void
    {
        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
        $this->assertInstanceOf(WhatFailureGroupHandler::class, $handler);
    }
}