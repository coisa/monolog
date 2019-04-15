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

namespace CoiSA\Monolog\Git;

use Gitonomy\Git\Exception\InvalidArgumentException;
use Gitonomy\Git\Repository;
use Psr\Container\ContainerInterface;

/**
 * Class GitonomyGitRepositoryFactory
 *
 * @package CoiSA\Monolog\Git
 */
final class GitonomyGitRepositoryFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @throws InvalidArgumentException
     *
     * @return Repository
     */
    public function __invoke(ContainerInterface $container): Repository
    {
        $dir = \getcwd();

        if ($container->has('config')) {
            $config = $container->get('config');

            if (isset($config[self::class])
                && \is_string($config[self::class])
            ) {
                $dir = $config[self::class];
            }
        }

        return new Repository($dir);
    }
}
