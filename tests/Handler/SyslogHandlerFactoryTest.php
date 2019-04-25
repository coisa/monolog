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

use CoiSA\Monolog\Handler\SyslogHandlerFactory;
use Monolog\Handler\SyslogHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class SyslogHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var SyslogHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->factory = new SyslogHandlerFactory();
    }

    public function testFactoryWithoutConfigReturnSyslogHandlerWithDefaultIdentity(): void
    {
        $this->container->has('config')->willReturn(false);

        $handler = ($this->factory)($this->container->reveal());

        $ident = new \ReflectionProperty(SyslogHandler::class, 'ident');
        $ident->setAccessible(true);

        $this->assertInstanceOf(SyslogHandler::class, $handler);
        $this->assertEquals(SyslogHandlerFactory::DEFAULT_ID, $ident->getValue($handler));
    }

    public function testFactoryWithoutConfigIndexReturnSyslogHandlerWithDefaultName(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            \uniqid('test', true) => \uniqid('test', true)
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $ident = new \ReflectionProperty(SyslogHandler::class, 'ident');
        $ident->setAccessible(true);

        $this->assertInstanceOf(SyslogHandler::class, $handler);
        $this->assertEquals(SyslogHandlerFactory::DEFAULT_ID, $ident->getValue($handler));
    }

    public function testFactoryWithNonStringConfigReturnSyslogHandlerWithDefaultName(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            SyslogHandlerFactory::class => [
                \uniqid('test', true)
            ]
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $ident = new \ReflectionProperty(SyslogHandler::class, 'ident');
        $ident->setAccessible(true);

        $this->assertInstanceOf(SyslogHandler::class, $handler);
        $this->assertEquals(SyslogHandlerFactory::DEFAULT_ID, $ident->getValue($handler));
    }

    public function testFactoryWithStringConfigReturnSyslogHandlerWithConfiguredIdentity(): void
    {
        $uniq = \uniqid('test', true);

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            SyslogHandlerFactory::class => $uniq
        ]);

        $handler = ($this->factory)($this->container->reveal());

        $ident = new \ReflectionProperty(SyslogHandler::class, 'ident');
        $ident->setAccessible(true);

        $this->assertInstanceOf(SyslogHandler::class, $handler);
        $this->assertEquals($uniq, $ident->getValue($handler));
    }
}
