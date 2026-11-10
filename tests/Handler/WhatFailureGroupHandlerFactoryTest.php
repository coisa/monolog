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

namespace CoiSA\Monolog\Test\Handler;

use CoiSA\Monolog\Handler\WhatFailureGroupHandlerFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * Class WhatFailureGroupHandlerFactoryTest
 *
 * @package CoiSA\Monolog\Test\Handler
 */
final class WhatFailureGroupHandlerFactoryTest extends TestCase
{
    /**
     * The healthy destination receives both individual and buffered records.
     */
    public function testFailedDestinationDoesNotPreventLaterHandlers(): void
    {
        $failed = $this->createMock(StreamHandler::class);
        $failed->expects($this->once())->method('handle')->willThrowException(new \RuntimeException('unavailable'));
        $failed->expects($this->once())->method('handleBatch')
            ->willThrowException(new \RuntimeException('unavailable'));
        $healthy = new TestHandler();

        $dependencies = ['services' => [StreamHandler::class => $failed, TestHandler::class => $healthy]];
        $container = new ServiceManager($dependencies);
        $container->setService('config', ['dependencies' => $dependencies]);
        $handler = (new WhatFailureGroupHandlerFactory())($container);

        $record = [
            'message'    => 'single',
            'context'    => [],
            'level'      => Logger::INFO,
            'level_name' => 'INFO',
            'channel'    => 'test',
            'datetime'   => new \DateTimeImmutable(),
            'extra'      => [],
        ];
        $handler->handle($record);
        $record['message'] = 'batch';
        $handler->handleBatch([$record]);

        $this->assertSame(['single', 'batch'], \array_column($healthy->getRecords(), 'message'));
    }
}
