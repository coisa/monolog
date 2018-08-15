<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

namespace CoiSA\Monolog\Container\ConfigProvider;

use CoiSA\Monolog\Log;
use Monolog\Processor;

/**
 * Class ProcessorsConfigProvider
 *
 * @package CoiSA\Monolog\Container\ConfigProvider
 */
class ProcessorsConfigProvider
{
    /**
     * Return dependency mappings for logger processors
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Return dependency mappings for logger processors
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'services' => [
                ProcessorsConfigProvider::class => $this
            ],
            'invokables' => [
                Processor\PsrLogMessageProcessor::class   => Processor\PsrLogMessageProcessor::class,
                Processor\UidProcessor::class             => Processor\UidProcessor::class,
                Processor\ProcessIdProcessor::class       => Processor\ProcessIdProcessor::class,
                Processor\MemoryUsageProcessor::class     => Processor\MemoryUsageProcessor::class,
                Processor\MemoryPeakUsageProcessor::class => Processor\MemoryPeakUsageProcessor::class,
                Processor\IntrospectionProcessor::class   => Processor\IntrospectionProcessor::class,
                Processor\WebProcessor::class             => Processor\WebProcessor::class,
                Log\Processor\ElapsedTimeProcessor::class => Log\Processor\ElapsedTimeProcessor::class,
            ],
        ];
    }
}