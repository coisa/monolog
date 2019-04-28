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

namespace CoiSA\Monolog\Test\Initializer;

use CoiSA\Monolog\Initializer\LoggerAwareInitializer;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class LoggerAwareInitializerTest
 *
 * @package CoiSA\Monolog\Test\Initializer
 */
final class LoggerAwareInitializerTest extends TestCase
{
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;

    /**
     * @var LoggerInterface|ObjectProphecy
     */
    private $logger;

    /**
     * @var LoggerAwareInterface|ObjectProphecy
     */
    private $instance;

    /**
     * @var LoggerAwareInitializer
     */
    private $initializer;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->logger    = $this->prophesize(LoggerInterface::class);
        $this->instance  = $this->prophesize(LoggerAwareInterface::class);

        $this->initializer = new LoggerAwareInitializer();

        $this->container->get(LoggerInterface::class)->will([$this->logger, 'reveal']);
    }

    public function testInvokeRaisesContainerException(): void
    {
        $this->container->get(LoggerInterface::class)->willThrow(ServiceNotFoundException::class);

        $this->expectException(ContainerExceptionInterface::class);
        ($this->initializer)($this->container->reveal(), $this->instance->reveal());
    }

    public function testRaiseTypeErrorWhenContainerNotReturnLogger(): void
    {
        $this->container->get(LoggerInterface::class)->willReturn(new \stdClass());

        $this->expectException(\TypeError::class);
        ($this->initializer)($this->container->reveal(), $this->instance->reveal());
    }

    public function testInstanceLoggerAwareWillSetLogger(): void
    {
        $this->instance->setLogger($this->logger->reveal())->shouldBeCalledOnce();

        ($this->initializer)($this->container->reveal(), $this->instance->reveal());
    }

    public function testInstanceNotLoggerAwareWillNotSetLogger(): void
    {
        $this->instance->setLogger($this->logger->reveal())->shouldNotBeCalled();

        ($this->initializer)($this->container->reveal(), new \stdClass());
    }
}
