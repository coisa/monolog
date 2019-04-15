<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Processor;

use CoiSA\Monolog\Git\LogInfoGit;
use Monolog\Processor\ProcessorInterface;

/**
 * Class GitProcessor
 *
 * @package CoiSA\Monolog\Processor
 */
final class GitProcessor implements ProcessorInterface
{
    /**
     * @var LogInfoGit
     */
    private $logInfoGit;

    /**
     * @var array
     */
    private $cache;

    /**
     * GitProcessor constructor.
     *
     * @param LogInfoGit $logInfoGit
     */
    public function __construct(LogInfoGit $logInfoGit)
    {
        $this->logInfoGit = $logInfoGit;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record): array
    {
        $record['extra']['git'] = $this->getCachedExtra();

        return $record;
    }

    /**
     * @return array
     */
    private function getExtra(): array
    {
        return [
            'release' => $this->logInfoGit->getRelease(),
            'branch'  => $this->logInfoGit->getCurrentBranch(),
            'commit'  => $this->logInfoGit->getCommit(),
        ];
    }

    /**
     * @return array
     */
    private function getCachedExtra(): array
    {
        if (\is_array($this->cache)) {
            return $this->cache;
        }

        $this->cache = $this->getExtra();

        return $this->cache;
    }
}
