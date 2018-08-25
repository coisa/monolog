<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
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
    public function register(Container $pimple)
    {
        if (isset($pimple['config'])) {
            $originalConfig = $pimple['config'];
        }

        $configProvider = new ConfigProvider();

        $config = new Config($configProvider());
        $config->configureContainer($pimple);

        if (isset($originalConfig)) {
            $pimple['config'] = array_merge_recursive($originalConfig, $pimple['config']);
        }
    }
}
