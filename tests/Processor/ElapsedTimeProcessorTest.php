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

namespace CoiSA\Monolog\Test\Processor;

use CoiSA\Monolog\Processor\ElapsedTimeProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Class GitProcessorTest
 *
 * @package CoiSA\Monolog\Test\Processor
 */
final class ElapsedTimeProcessorTest extends TestCase
{
    /** @var ElapsedTimeProcessor */
    private $processor;

    public function setUp(): void
    {
        $this->processor = new ElapsedTimeProcessor();
    }

    public function testConstructWithInvalidArgumentRaiseErrorType(): void
    {
        $this->expectException(\TypeError::class);
        new ElapsedTimeProcessor(new \stdClass());
    }

    public function testProcessorInvokeReturnArray()
    {
        $record = $this->processor->__invoke([]);
        $this->assertIsArray($record);

        return $record;
    }

    /**
     * @depends testProcessorInvokeReturnArray
     */
    public function testProcessorAddExtraElapsedTime(array $record)
    {
        $this->assertArrayHasKey('extra', $record);
        $this->assertArrayHasKey('elapsed_time', $record['extra']);

        return $record;
    }
}
