<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\ServiceProvider;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;
use Pimple\Container;

/**
 * Class PhalconServiceProvider
 *
 * @package CoiSA\Monolog\Container\ServiceProvider
 */
class PhalconServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers Monolog services on the given container.
     *
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $pimple = new Container();
        $pimple->register(new PimpleServiceProvider());

        foreach ($pimple->keys() as $key) {
            $di->setShared($key, $pimple->raw($key));
        }
    }
}
