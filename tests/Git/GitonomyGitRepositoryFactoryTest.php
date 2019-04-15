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

use CoiSA\Monolog\Git\GitonomyGitRepositoryFactory;
use Gitonomy\Git\Exception\InvalidArgumentException;
use Gitonomy\Git\Repository;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class GitonomyGitRepositoryFactoryTest
 *
 * @package CoiSA\Monolog\Test\Git
 */
final class GitonomyGitRepositoryFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var GitonomyGitRepositoryFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->factory = new GitonomyGitRepositoryFactory();
    }

    public function testFactoryWihoutConfigReturnRepositoryWithCurrentWorkingDir(): void
    {
        $this->container->has('config')->willReturn(false);

        $repository = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals(\getcwd(), $repository->getPath());
    }

    public function testFactoryWithEmptyConfigReturnRepositoryWithCurrentWorkingDir(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([]);

        $repository = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals(\getcwd(), $repository->getPath());
    }

    public function testFactoryWithInvalidConfigReturnRepositoryWithCurrentWorkingDir(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            GitonomyGitRepositoryFactory::class => new \stdClass()
        ]);

        $repository = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals(\getcwd(), $repository->getPath());
    }

    public function testFactoryWithConfigReturnRepositoryWithGivenPath(): void
    {
        $path = \sys_get_temp_dir();

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            GitonomyGitRepositoryFactory::class => $path
        ]);

        $repository = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(Repository::class, $repository);
        $this->assertEquals($path, $repository->getPath());
    }

    public function testFactoryRaisesExceptionWhenGivenPathNotExists(): void
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            GitonomyGitRepositoryFactory::class => \uniqid('test', true)
        ]);

        $this->expectException(InvalidArgumentException::class);

        ($this->factory)($this->container->reveal());
    }
}
