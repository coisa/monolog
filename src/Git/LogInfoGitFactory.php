<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Git;

use Gitonomy\Git\Repository;
use Psr\Container\ContainerInterface;

/**
 * Class LogInfoGitFactory
 *
 * @package CoiSA\Monolo\Git
 */
final class LogInfoGitFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LogInfoGit
     */
    public function __invoke(ContainerInterface $container): LogInfoGit
    {
        return new LogInfoGit(
            $container->get(Repository::class)
        );
    }
}
