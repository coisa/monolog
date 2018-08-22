<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Log\Processor
 */

namespace CoiSA\Monolog\Log\Processor;

/**
 * Class GitProcessor
 *
 * @package CoiSA\Monolog\Log\Processor
 */
class GitProcessor
{
    /**
     * @var null|array
     */
    private $commitInfo;

    /**
     * @var null|string
     */
    private $release;

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $git = $this->getCommitInfo();
        if (null === $git) {
            return $record;
        }
        $record['extra']['git'] = $git;

        $release = $this->getRelease();
        if (null === $release) {
            return $record;
        }
        $record['extra']['release'] = $release;

        return $record;
    }

    /**
     * Returns git commit & branch info
     *
     * @return array|null
     */
    private function getCommitInfo(): ?array
    {
        if (!isset($this->commitInfo)) {
            $branches = shell_exec('git branch -v --no-abbrev 2> /dev/null');

            if (false === $branches) {
                $this->commitInfo = false;
            }

            if (preg_match('{^\* (.+?)\s+([a-f0-9]{40})(?:\s|$)}m', $branches, $matches)) {
                $this->commitInfo = array(
                    'branch' => $matches[1],
                    'commit' => $matches[2],
                );
            }
        }

        return $this->commitInfo ?: null;
    }

    /**
     * Returns the release based on git tags
     *
     * @return null|string
     */
    private function getRelease(): ?string
    {
        if (!isset($this->release)) {
            shell_exec('git fetch --tags 2> /dev/null');

            $release = shell_exec('git tag | sort -r --version-sort | head -n1');
            $this->release = $release ? trim($release, "\n") : false;
        }

        return $this->release ?: null;
    }
}