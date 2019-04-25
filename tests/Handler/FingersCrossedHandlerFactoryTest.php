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

use CoiSA\Monolog\Handler\FingersCrossedHandlerFactory;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\WhatFailureGroupHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class FingersCrossedHandlerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class FingersCrossedHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|WhatFailureGroupHandler */
    private $handler;

    /** @var FingersCrossedHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->handler   = $this->prophesize(WhatFailureGroupHandler::class);

        $this->factory = new FingersCrossedHandlerFactory();

        $this->container->get(WhatFailureGroupHandler::class)->will([$this->handler, 'reveal']);
    }

    public function testFactoryRaiseExceptionWhenWhatFailureGroupHandlerNotFound(): void
    {
        $this->container->get(WhatFailureGroupHandler::class)->willThrow(new ServiceNotFoundException());

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotWhatFailureGroupHandler(): void
    {
        $this->container->get(WhatFailureGroupHandler::class)->willReturn(new \stdClass());

        $this->expectException(\RuntimeException::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithRavenClientReturnFingersCrossedHandler(): void
    {
        $handler = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(HandlerInterface::class, $handler);
        $this->assertInstanceOf(FingersCrossedHandler::class, $handler);
    }
}