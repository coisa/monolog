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

namespace CoiSA\Monolog\Processor;

use Monolog\Processor\ProcessorInterface;

/**
 * Class ProcessorAggregate
 *
 * @package CoiSA\Monolog\Processor
 */
final class ProcessorAggregate implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * ProcessorAggregate constructor.
     *
     * @param ProcessorInterface ...$processors
     */
    public function __construct(ProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    /**
     * @param array $records
     *
     * @return array
     */
    public function __invoke(array $records)
    {
        foreach ($this->processors as $processor) {
            $records = $processor($records);
        }

        return $records;
    }
}
