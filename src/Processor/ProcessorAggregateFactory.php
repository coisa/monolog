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
        // @FIXME merge all processors defined in the container
        return new ProcessorAggregate(
            $container->get(GitProcessor::class)
        );
    }
}
