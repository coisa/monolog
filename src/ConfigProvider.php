<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog;

use CoiSA\Monolog\Container\ConfigProvider\HandlersConfigProvider;
use CoiSA\Monolog\Container\ConfigProvider\LoggerConfigProvider;
use CoiSA\Monolog\Container\ConfigProvider\MiddlewareConfigProvider;
use CoiSA\Monolog\Container\ConfigProvider\ProcessorsConfigProvider;
use CoiSA\Monolog\Container\ConfigProvider\StrategiesConfigProvider;
use Monolog\Handler;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog
 */
final class ConfigProvider
{
    /** @const string Eager log entry write strategy */
    const EAGER = Handler\GroupHandler::class;

    /** @const string Waiting for an error log entry write strategy */
    const OPTIMISTIC = Handler\FingersCrossedHandler::class;

    /** @const string Lazy log entry write strategy. Writes only in the end of execution */
    const LAZY = Handler\BufferHandler::class;

    /** @const string Lazy log entry write strategy. Deduplicate log entries then write in the end of execution */
    const DEDUPLICATED = Handler\DeduplicationHandler::class;

    /** @const string NOT log entry at ALL! */
    const DISABLED = Handler\NullHandler::class;

    /**
     * @var ConfigAggregator Merged dependency mappings and configs
     */
    private $config;

    /**
     * ConfigProvider constructor.
     *
     * @param null|string $strategy optional Default handler strategy
     */
    public function __construct(?string $strategy = self::EAGER)
    {
        $this->config = new ConfigAggregator([
            new ArrayProvider([
                __CLASS__ => [
                    'strategy' => $strategy
                ],
            ]),
            LoggerConfigProvider::class,
            StrategiesConfigProvider::class,
            HandlersConfigProvider::class,
            ProcessorsConfigProvider::class,
            MiddlewareConfigProvider::class,
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
