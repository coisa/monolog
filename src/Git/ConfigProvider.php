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

use Gitonomy\Git\Repository;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog\Git
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Return dependency mappings for logger processors
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories' => [
                LogInfoGit::class => LogInfoGitFactory::class,
                Repository::class => GitonomyGitRepositoryFactory::class,
            ]
        ];
    }
}
