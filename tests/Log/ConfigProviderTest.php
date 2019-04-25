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

namespace CoiSA\Monolog\Test\Log;

use CoiSA\Monolog\Log\ConfigProvider;
use CoiSA\Monolog\Test\AbstractConfigProviderTest;

/**
 * Class ConfigProviderTest
 *
 * @package CoiSA\Monolog\Test\Log
 */
final class ConfigProviderTest extends AbstractConfigProviderTest
{
    protected function getConfigProvider(): callable
    {
        return new ConfigProvider();
    }
}