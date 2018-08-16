<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog
 */

declare(strict_types=1);

namespace CoiSA\Monolog;

use CoiSA\Monolog\Container\ConfigProvider\{
    LoggerConfigProvider, StrategiesConfigProvider, HandlersConfigProvider, ProcessorsConfigProvider
};
use Monolog\Handler;
use Zend\ConfigAggregator\{
    ArrayProvider, ConfigAggregator
};

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog
 */
final class ConfigProvider
{
    /**
     * @var ConfigAggregator Merged dependency mappings and configs
     */
    private $config;

    /**
     * ConfigProvider constructor.
     *
     * @param string|null $strategy optional Default handler strategy
     */
    public function __construct(?string $strategy = Handler\GroupHandler::class)
    {
        $providers = $this->getProviders($strategy);
        $this->config = new ConfigAggregator($providers);
    }

    /**
     * Returns component config
     *
     * @return array
     */
    public function __invoke(): array
    {
        return $this->config->getMergedConfig();
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        $mergedConfig = $this->config->getMergedConfig();

        return $mergedConfig['dependencies'];
    }

    /**
     * Returns the default handler strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        $mergedConfig = $this->config->getMergedConfig();

        return $mergedConfig[__CLASS__]['strategy'];
    }

    /**
     * Returns collection of Config Providers to load
     *
     * @param null|string $strategy
     *
     * @return array
     */
    private function getProviders(?string $strategy): array
    {
        return [
            new ArrayProvider([
                __CLASS__      => [
                    'strategy' => $strategy
                ],
                'dependencies' => [
                    'services' => [
                        __CLASS__ => $this
                    ]
                ]
            ]),
            LoggerConfigProvider::class,
            StrategiesConfigProvider::class,
            HandlersConfigProvider::class,
            ProcessorsConfigProvider::class,
        ];
    }
}
