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

namespace CoiSA\Monolog\ServiceProvider;

use CoiSA\Monolog\ConfigProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zend\Pimple\Config\Config;
use Zend\Stdlib\ArrayUtils;

/**
 * Class PimpleServiceProvider
 *
 * @package CoiSA\Monolog\ServiceProvider
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
        $config = (new ConfigProvider())();

        if ($pimple->offsetExists('config')) {
            $config = ArrayUtils::merge(
                $config,
                $pimple->offsetGet('config')
            );
        }

        $config = new Config($config);
        $config->configureContainer($pimple);
    }
}
