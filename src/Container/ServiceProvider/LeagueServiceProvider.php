<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
 */

namespace CoiSA\Monolog\Container\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Pimple\Container;

/**
 * Class LeagueServiceProvider
 *
 * @package CoiSA\Monolog\Container\ServiceProvider
 */
class LeagueServiceProvider extends AbstractServiceProvider
{
    use ConfigProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function provides(string $service): bool
    {
        $dependencies = call_user_func_array('array_merge', $this->config->getDependencies());

        return array_key_exists($service, $dependencies);
    }

    /**
     * Registers Monolog services on the given container.
     */
    public function register()
    {
        $pimple = new Container();
        $pimple->register(new PimpleServiceProvider($this->config));

        foreach ($pimple->keys() as $key) {
            $this->getContainer()->add($key, $pimple->raw($key));
        }
    }
}