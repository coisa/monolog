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

namespace CoiSA\Monolog\Processor;

use CoiSA\Monolog\Git\LogInfoGit;
use Psr\Container\ContainerInterface;

/**
 * Class GitProcessorFactory
 *
 * @package CoiSA\Processor
 */
final class GitProcessorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return GitProcessor
     */
    public function __invoke(ContainerInterface $container): GitProcessor
    {
        return new GitProcessor(
            $container->get(LogInfoGit::class)
        );
    }
}
