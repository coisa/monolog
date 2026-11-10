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

namespace CoiSA\Monolog\Test\Processor;

use CoiSA\Monolog\ConfigProvider;
use CoiSA\Monolog\Git\GitonomyGitRepositoryFactory;
use CoiSA\Monolog\Processor\ConfigProvider as ProcessorConfigProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * Regression coverage for the configured logger processors.
 */
final class ProcessorAggregateFactoryTest extends TestCase
{
    /**
     * Default processors still work when the application has no Git checkout.
     */
    public function testDefaultLoggerProcessesRecordsWithoutGit(): void
    {
        $directory = \sys_get_temp_dir() . '/monolog-' . \bin2hex(\random_bytes(8));
        \mkdir($directory, 0700);

        try {
            $config = (new ConfigProvider())();
            $config[GitonomyGitRepositoryFactory::class] = $directory;
            $handler = new TestHandler();
            $config['dependencies']['aliases'][HandlerInterface::class] = TestHandler::class;
            $config['dependencies']['services'][TestHandler::class] = $handler;
            $config[ProcessorConfigProvider::class][] = 'application.processor';
            $config['dependencies']['services']['application.processor'] = new class implements ProcessorInterface {
                /**
                 * Add an application-specific field to the record.
                 *
                 * @param array $record
                 *
                 * @return array
                 */
                public function __invoke(array $record)
                {
                    $record['extra']['application'] = 'fixture';

                    return $record;
                }
            };

            $container = new ServiceManager($config['dependencies']);
            $container->setService('config', $config);
            $container->get(Logger::class)->info('Hello {name}', ['name' => 'Ada']);

            $record = $handler->getRecords()[0];
            $this->assertSame('Hello Ada', $record['message']);
            $this->assertArrayHasKey('uid', $record['extra']);
            $this->assertArrayHasKey('process_id', $record['extra']);
            $this->assertSame('fixture', $record['extra']['application']);
            $this->assertArrayNotHasKey('git', $record['extra']);
        } finally {
            \rmdir($directory);
        }
    }
}
