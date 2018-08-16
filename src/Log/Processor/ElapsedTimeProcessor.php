<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Log\Processor
 */

namespace CoiSA\Monolog\Log\Processor;

/**
 * Class ElapsedTimeProcessor
 *
 * @package CoiSA\Monolog\Log\Processor
 */
class ElapsedTimeProcessor
{
    /**
     * @var \DateTimeImmutable Processor start time evaluator
     */
    private $startTime;

    /**
     * ElapsedTimeProcessor constructor.
     *
     * @param float $startTime optional Explicit start time
     */
    public function __construct(float $startTime = null)
    {
        $this->setStartTime($startTime ?? $_SERVER['REQUEST_TIME_FLOAT']);
    }

    /**
     * Add elapsed time to the log extra fields
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record): array
    {
        $dateTimeDiff = $this->startTime->diff(new \DateTimeImmutable());

        $record['extra']['elapsed_time'] = $dateTimeDiff->format('%H:%I:%S');

        return $record;
    }

    /**
     * Set the processor start time
     *
     * @param float $startTime
     */
    private function setStartTime(float $startTime): void
    {
        $startTime = number_format($startTime, 6, '.', '');

        $this->startTime = \DateTimeImmutable::createFromFormat('U.u', $startTime);
    }

}