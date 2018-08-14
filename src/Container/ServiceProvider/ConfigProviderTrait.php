<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ServiceProvider
 */

namespace CoiSA\Monolog\Container\ServiceProvider;

use CoiSA\Monolog\ConfigProvider;

/**
 * Trait ConfigProviderTrait
 *
 * @package CoiSA\Monolog\Container\ServiceProvider
 */
trait ConfigProviderTrait
{
    /**
     * @var ConfigProvider Config for the service provider
     */
    private $config;

    /**
     * PimpleServiceProvider constructor.
     *
     * @param ConfigProvider $configProvider optional Customized config provider
     */
    public function __construct(ConfigProvider $configProvider = null)
    {
        $this->config = $configProvider ?? new ConfigProvider();
    }
}