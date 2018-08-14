<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog
 */

declare(strict_types=1);

namespace CoiSA\Monolog;

use CoiSA\Monolog\Container\Factory;
use Monolog\Handler;
use Monolog\Logger;
use Monolog\Processor;
use Psr\Log\LoggerInterface;

/**
 * Class ConfigProvider
 *
 * @package CoiSA\Monolog
 */
class ConfigProvider
{
    /**
     * @var string Default handler strategy
     */
    private $strategy;

    /**
     * ConfigProvider constructor.
     *
     * @param string|null $strategy optional Default handler strategy
     */
    public function __construct(?string $strategy = Handler\GroupHandler::class)
    {
        $this->setStrategy($strategy);
    }

    /**
     * Returns component config
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
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return array_merge_recursive(
            $this->getLoggerDefinitions(),
            $this->getStrategiesDefinitions(),
            $this->getHandlersDefinitions(),
            $this->getProcessorsDefinitions()
        );
    }

    /**
     * Return dependency mappings for the Logger
     *
     * @return array
     */
    private function getLoggerDefinitions(): array
    {
        return [
            'services'  => [
                ConfigProvider::class => $this
            ],
            'aliases'   => [
                'logger'               => Logger::class,
                LoggerInterface::class => Logger::class,
            ],
            'factories' => [
                Logger::class                   => Factory\LoggerFactory::class,
                Handler\HandlerInterface::class => Factory\HandlerFactory::class,
            ],
        ];
    }

    /**
     * Return dependency mappings for logging strategies
     *
     * @return array
     */
    private function getStrategiesDefinitions(): array
    {
        return [
            'factories' => [
                Handler\GroupHandler::class          => Factory\GroupHandlerFactory::class,
                Handler\FingersCrossedHandler::class => Factory\FingersCrossedHandlerFactory::class,
                Handler\BufferHandler::class         => Factory\BufferHandlerFactory::class,
                Handler\DeduplicationHandler::class  => Factory\DeduplicationHandlerFactory::class,
            ]
        ];
    }

    /**
     * Return dependency mappings for logger handlers
     *
     * @return array
     */
    private function getHandlersDefinitions(): array
    {
        return [
            'invokables' => [
                Handler\NullHandler::class           => Handler\NullHandler::class,
                Handler\BrowserConsoleHandler::class => Handler\BrowserConsoleHandler::class,
                Handler\ChromePHPHandler::class      => Handler\ChromePHPHandler::class,
            ],
            'factories'  => [
                Handler\StreamHandler::class => Factory\StreamHandlerFactory::class,
                Handler\SyslogHandler::class => Factory\SyslogHandlerFactory::class,
                Handler\RedisHandler::class  => Factory\RedisHandlerFactory::class,
                Handler\RavenHandler::class  => Factory\RavenHandlerFactory::class,
            ],
        ];
    }

    /**
     * Return dependency mappings for logger processors
     *
     * @return array
     */
    private function getProcessorsDefinitions(): array
    {
        return [
            'invokables' => [
                Processor\PsrLogMessageProcessor::class   => Processor\PsrLogMessageProcessor::class,
                Processor\UidProcessor::class             => Processor\UidProcessor::class,
                Processor\ProcessIdProcessor::class       => Processor\ProcessIdProcessor::class,
                Processor\MemoryUsageProcessor::class     => Processor\MemoryUsageProcessor::class,
                Processor\MemoryPeakUsageProcessor::class => Processor\MemoryPeakUsageProcessor::class,
                Processor\IntrospectionProcessor::class   => Processor\IntrospectionProcessor::class,
                Processor\WebProcessor::class             => Processor\WebProcessor::class,
                Log\Processor\ElapsedTimeProcessor::class => Log\Processor\ElapsedTimeProcessor::class,
            ]
        ];
    }

    /**
     * Returns defined classes on a container definition set.
     *
     * @param array $definitions
     *
     * @return array|mixed
     */
    private function getDefinissionsClasses(array $definitions)
    {
        $flatten = call_user_func_array('array_merge', $definitions);

        return array_keys($flatten);
    }

    /**
     * Set the default handler strategy.
     * Set to null if you don't want handle log entries.
     *
     * @param string|null $handler Default handler strategy
     *
     * @return ConfigProvider
     */
    public function setStrategy(?string $handler): ConfigProvider
    {
        if ($handler === null) {
            $handler = Handler\NullHandler::class;
        }

        $definitions = $this->getStrategiesDefinitions();
        $strategies = $this->getDefinissionsClasses($definitions);

        if (in_array($handler, $strategies)) {
            $this->strategy = $handler;
        }

        if (!$this->strategy) {
            $this->strategy = Handler\NullHandler::class;
        }

        return $this;
    }

    /**
     * Returns the default handler strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Return all handlers for the GroupHandler
     *
     * @return array
     */
    public function getHandlers(): array
    {
        $definitions = $this->getHandlersDefinitions();

        return $this->getDefinissionsClasses($definitions);
    }
}
