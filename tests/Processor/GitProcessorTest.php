<?php declare(strict_types=1);
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
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class GitProcessorTest
 *
 * @package CoiSA\Monolog\Test\Processor
 */
final class GitProcessorTest extends TestCase
{
    /** @var LogInfoGit|ObjectProphecy */
    private $logInfoGit;

    /** @var GitProcessor */
    private $processor;

    /** @var array */
    private $expected;

    public function setUp(): void
    {
        $this->logInfoGit = $this->prophesize(LogInfoGit::class);

        $this->expected = [
            'release' => \uniqid('release', true),
            'branch'  => \uniqid('branch', true),
            'commit'  => \uniqid('commit', true),
        ];

        $this->logInfoGit->getRelease()->willReturn($this->expected['release']);
        $this->logInfoGit->getCurrentBranch()->willReturn($this->expected['branch']);
        $this->logInfoGit->getCommit()->willReturn($this->expected['commit']);

        $this->processor = new GitProcessor($this->logInfoGit->reveal());
    }

    public function testConstructWithInvalidArgumentRaiseErrorType(): void
    {
        $this->expectException(\TypeError::class);
        new GitProcessor(new \stdClass());
    }

    public function testProcessorInvokeReturnArray()
    {
        $record = $this->processor->__invoke([]);
        $this->assertIsArray($record);

        return $record;
    }

    public function testProcessorReturnArrayWithExtraLogInfoGitDetails(): void
    {
        $this->logInfoGit->getRelease()->shouldBeCalledOnce();
        $this->logInfoGit->getCurrentBranch()->shouldBeCalledOnce();
        $this->logInfoGit->getCommit()->shouldBeCalledOnce();

        $expected = [
            'extra' => [
                'git' => [
                    'release' => $this->expected['release'],
                    'branch'  => $this->expected['branch'],
                    'commit'  => $this->expected['commit'],
                ]
            ]
        ];

        // Force test cache
        for ($i = 0; $i < 5; $i++) {
            $record = $this->processor->__invoke([]);
            $this->assertSame($expected, $record);
        }
    }
}
