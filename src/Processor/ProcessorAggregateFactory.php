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

/**
 * @package CoiSA\Monolog\Processor
 */
namespace CoiSA\Monolog\Processor;

use CoiSA\Monolog\Git\GitMetadataUnavailable;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ProcessorAggregateFactory
 *
 * @package CoiSA\Monolog\Processor
 */
final class ProcessorAggregateFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProcessorAggregate
     */
    public function __invoke(ContainerInterface $container): ProcessorAggregate
    {
        $config     = $container->has('config') ? $container->get('config') : [];
        $services   = $config[ConfigProvider::class] ?? [GitProcessor::class];
        $services   = \array_diff($services, [ProcessorInterface::class, ProcessorAggregate::class]);
        $processors = [];

        foreach ($services as $service) {
            try {
                $processors[] = $container->get($service);
            } catch (GitMetadataUnavailable | ContainerExceptionInterface $exception) {
                if ($service !== GitProcessor::class || !$this->isGitMetadataUnavailable($exception)) {
                    throw $exception;
                }
            }
        }

        return new ProcessorAggregate(...$processors);
    }

    /**
     * Check for the optional Git service's cause through container wrappers.
     *
     * @param \Throwable $exception
     *
     * @return bool
     */
    private function isGitMetadataUnavailable(\Throwable $exception): bool
    {
        for ($cause = $exception; $cause !== null; $cause = $cause->getPrevious()) {
            if ($cause instanceof GitMetadataUnavailable) {
                return true;
            }
        }

        return false;
    }
}
