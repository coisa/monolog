<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use CoiSA\Monolog\ConfigProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Monolog\Registry;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class LoggerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class LoggerFactory
{
    /**
     * @const string Default logger context name
     */
    const DEFAULT_NAME = 'monolog';

    /**
     * @var string Logger context name
     */
    private $name;

    /**
     * LoggerFactory constructor.
     *
     * @param string $name optional Logger context name
     */
    public function __construct(string $name = self::DEFAULT_NAME)
    {
        $this->name = $name;
    }

    /**
     * Logger service factory
     *
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container)
    {
        try {
            $handler = $container->get(HandlerInterface::class);
        } catch (ContainerExceptionInterface $exception) {
            $handler = new NullHandler();
        }

        if (!$handler instanceof HandlerInterface) {
            $handler = new NullHandler();
        }

        $logger = new Logger($this->name);
        $logger->pushHandler($handler);

        $this->pushProcessors($container, $logger);

        // Alternative access to logger **not encouraged**
        Registry::addLogger($logger);

        return $logger;
    }

    /**
     * Set logger processors
     *
     * @param ContainerInterface $container
     * @param Logger $logger
     */
    private function pushProcessors(ContainerInterface $container, Logger $logger): void
    {
        try {
            $configProvider = $container->get(ConfigProvider::class);
        } catch (ContainerExceptionInterface $exception) {
            return;
        }

        foreach ($configProvider->getProcessors() as $processorClass) {
            try {
                $processor = $container->get($processorClass);
            } catch (ContainerExceptionInterface $exception) {
                continue;
            }

            $logger->pushProcessor($processor);
        }
    }
}