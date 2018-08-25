<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\ServiceProvider;

use CoiSA\Monolog\ConfigProvider;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pimple\Container;

/**
 * Class LeagueServiceProvider
 *
 * @package CoiSA\Monolog\Container\ServiceProvider
 *
 * @method \League\Container\Container getContainer
 */
class LeagueServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provides(string $service): bool
    {
        $configProvider = new ConfigProvider();
        $dependencies = array_merge(...array_values($configProvider->getDependencies()));

        return array_key_exists($service, $dependencies);
    }

    /**
     * Registers Monolog services on the given container.
     */
    public function register()
    {
        $pimple = new Container();
        $pimple->register(new PimpleServiceProvider());

        foreach ($pimple->keys() as $key) {
            $this->getContainer()->add($key, $pimple->raw($key));
        }
    }
}
