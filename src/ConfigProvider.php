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
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog;

use CoiSA\Monolog\Container\ConfigProvider\LoggerConfigProvider;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;

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
     * @param null|string $strategy optional Default handler strategy
     */
    public function __construct(?string $strategy = StrategyInterface::EAGER)
    {
        $this->config = new ConfigAggregator([
            new ArrayProvider([
                __CLASS__ => [
                    'strategy' => $strategy
                ],
            ]),
            LoggerConfigProvider::class,
            Git\ConfigProvider::class,
            Handler\ConfigProvider::class,
            Middleware\ConfigProvider::class,
            Processor\ConfigProvider::class,
        ]);
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
}
