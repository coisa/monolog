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

namespace CoiSA\Monolog\Container\ServiceProvider;

use CoiSA\Monolog\ConfigProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zend\Pimple\Config\Config;

/**
 * Class PimpleServiceProvider
 *
 * @package CoiSA\Monolog\Container\ServiceProvider
 */
class PimpleServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers Monolog services on the given container.
     *
     * @param Container $pimple
     */
    public function register(Container $pimple): void
    {
        if (isset($pimple['config'])) {
            $originalConfig = $pimple['config'];
        }

        $configProvider = new ConfigProvider();

        $config = new Config($configProvider());
        $config->configureContainer($pimple);

        if (isset($originalConfig)) {
            $pimple['config'] = \array_merge_recursive($originalConfig, $pimple['config']);
        }
    }
}
