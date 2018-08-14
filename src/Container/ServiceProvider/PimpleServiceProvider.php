<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
 */

namespace CoiSA\Monolog\Container\ServiceProvider;

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
    use ConfigProviderTrait;

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

        $config = new Config([
            'dependencies' => $this->config->getDependencies()
        ]);
        $pimple[Config::class] = $config;

        $config->configureContainer($pimple);

        if (isset($originalConfig)) {
            $pimple['config'] = $originalConfig;
        } else {
            unset($pimple['config']);
        }
    }
}