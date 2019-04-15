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
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Processor;

use CoiSA\Monolog\Git\LogInfoGit;
use CoiSA\Monolog\Processor\GitProcessor;
use CoiSA\Monolog\Processor\GitProcessorFactory;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class GitProcessorFactoryTest
 *
 * @package CoiSA\Monolog\Test\Processor
 */
final class GitProcessorFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var LogInfoGit|ObjectProphecy */
    private $logInfoGit;

    /** @var GitProcessorFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container  = $this->prophesize(ContainerInterface::class);
        $this->logInfoGit = $this->prophesize(LogInfoGit::class);

        $this->factory = new GitProcessorFactory();
    }

    public function testFactoryRaiseExceptionWhenLogInfoGitNotFound(): void
    {
        $this->container->get(LogInfoGit::class)->willThrow(new ServiceNotFoundException());

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotLogInfoGit(): void
    {
        $this->container->get(LogInfoGit::class)->willReturn(new \stdClass());

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithLogInfoGitReturnGitProcessor(): void
    {
        $this->container->get(LogInfoGit::class)->will([$this->logInfoGit, 'reveal']);

        $processor = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(ProcessorInterface::class, $processor);
        $this->assertInstanceOf(GitProcessor::class, $processor);
    }
}
