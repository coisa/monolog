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

namespace CoiSA\Monolog\Test\Git;

use CoiSA\Monolog\Git\LogInfoGit;
use CoiSA\Monolog\Git\LogInfoGitFactory;
use Gitonomy\Git\Repository;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class LogInfoGitFactoryTest
 *
 * @package CoiSA\Monolog\Test\Git
 */
final class LogInfoGitFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var LogInfoGit|ObjectProphecy */
    private $repository;

    /** @var LogInfoGitFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container  = $this->prophesize(ContainerInterface::class);
        $this->repository = $this->prophesize(Repository::class);

        $this->factory = new LogInfoGitFactory();
    }

    public function testFactoryRaiseExceptionWhenRepositoryNotFound(): void
    {
        $this->container->get(Repository::class)->willThrow(new ServiceNotFoundException());

        $this->expectException(NotFoundExceptionInterface::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryRaiseExceptionWhenServiceIsNotRepository(): void
    {
        $this->container->get(Repository::class)->willReturn(new \stdClass());

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }

    public function testFactoryWithRepositoryReturnLogInfoGit(): void
    {
        $this->container->get(Repository::class)->will([$this->repository, 'reveal']);

        $object = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(LogInfoGit::class, $object);
    }
}
