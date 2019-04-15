<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Git;

use CoiSA\Monolog\Git\LogInfoGit;
use CoiSA\Monolog\Git\LogInfoGitFactory;
use CoiSA\Monolog\Processor\GitProcessor;
use CoiSA\Monolog\Processor\GitProcessorFactory;
use Gitonomy\Git\Commit;
use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Tag;
use Gitonomy\Git\ReferenceBag;
use Gitonomy\Git\Repository;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class LogInfoGitTest
 *
 * @package CoiSA\Monolog\Test\Git
 */
final class LogInfoGitTest extends TestCase
{
    /** @var LogInfoGit|ObjectProphecy */
    private $repository;

    /** @var ReferenceBag|ObjectProphecy */
    private $referenceBag;

    /** @var Branch|ObjectProphecy */
    private $branch;

    /** @var Commit|ObjectProphecy */
    private $commit;

    /** @var Tag|ObjectProphecy */
    private $tag;

    /** @var LogInfoGit */
    private $logInfoGit;

    public function setUp(): void
    {
        $this->repository = $this->prophesize(Repository::class);
        $this->referenceBag = $this->prophesize(ReferenceBag::class);
        $this->branch = $this->prophesize(Branch::class);
        $this->commit = $this->prophesize(Commit::class);
        $this->tag = $this->prophesize(Tag::class);

        $this->repository->isBare()->willReturn(false);
        $this->repository->getReferences()->will([$this->referenceBag, 'reveal']);
        $this->repository->getHeadCommit()->will([$this->commit, 'reveal']);

        $this->referenceBag->getBranches()->willReturn([$this->branch->reveal()]);
        $this->referenceBag->getTags()->willReturn([$this->tag->reveal()]);

        $this->branch->getName()->willReturn(uniqid('branch', true));
        $this->commit->getHash()->willReturn(uniqid('hash', true));
        $this->tag->getName()->willReturn(uniqid('tag', true));

        $this->logInfoGit = new LogInfoGit($this->repository->reveal());
    }

    public function testConstructWithBareRepositoryRaisesRuntimeException()
    {
        $this->expectException(\RuntimeException::class);
        $this->repository->isBare()->willReturn(true);

        new LogInfoGit($this->repository->reveal());
    }

    public function testGetCurrentBranchReturnRepositoryReferenceFirstBranchName()
    {
        $branch = $this->branch->reveal();

        $this->assertSame($branch->getName(), $this->logInfoGit->getCurrentBranch());
    }

    public function testGetCommitReturnRepositoryCommitHash()
    {
        $commit = $this->commit->reveal();

        $this->assertSame($commit->getHash(), $this->logInfoGit->getCommit());
    }

    public function testGetReleaseReturnRepositoryLastTag()
    {
        $tag = $this->tag->reveal();

        $this->assertSame($tag->getName(), $this->logInfoGit->getRelease());
    }

    public function testGetReleaseReturnNullWithoutRepositoryTag()
    {
        $this->referenceBag->getTags()->willReturn([]);

        $this->assertNull($this->logInfoGit->getRelease());
    }
}
