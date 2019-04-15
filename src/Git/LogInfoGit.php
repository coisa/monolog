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

namespace CoiSA\Monolog\Git;

use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Tag;
use Gitonomy\Git\Repository;

/**
 * Class LogInfoGit
 *
 * @package CoiSA\Monolog\Git
 */
class LogInfoGit
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * GitProcessor constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        if ($repository->isBare()) {
            throw new \RuntimeException(\sprintf(
                'Directory "%s" does not exist or is not a directory',
                $repository->getGitDir()
            ));
        }

        $this->repository = $repository;
    }

    /**
     * @return null|string
     */
    public function getCurrentBranch(): ?string
    {
        /** @var Branch[] $branches */
        $branches      = $this->repository->getReferences()->getBranches();
        $currentBranch = \current($branches);

        return $currentBranch->getName();
    }

    /**
     * @return string
     */
    public function getCommit(): string
    {
        return $this->repository->getHeadCommit()->getHash();
    }

    /**
     * Returns the release based on git tags
     *
     * @return null|string
     */
    public function getRelease(): ?string
    {
        /** @var Tag[] $tags */
        $tags = $this->repository->getReferences()->getTags();

        if (empty($tags)) {
            return null;
        }

        $lastTag = \end($tags);

        return $lastTag->getName();
    }
}
