<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class DeduplicationHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class DeduplicationHandlerFactory
{
    /**
     * @var string Fingers crossed handler
     */
    private $handler;

    /**
     * @var int Log level
     */
    private $level;

    /**
     * FingersCrossedHandlerFactory constructor.
     *
     * @param string $handler
     * @param int $level
     */
    public function __construct(string $handler = GroupHandler::class, int $level = Logger::DEBUG)
    {
        $this->handler = $handler;
        $this->level = $level;
    }

    /**
     * Fingers crossed handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return DeduplicationHandler
     */
    public function __invoke(ContainerInterface $container): DeduplicationHandler
    {
        try {
            $handler = $container->get($this->handler);
        } catch (ContainerExceptionInterface $exception) {
            $handler = new NullHandler();
        }

        return new DeduplicationHandler($handler, null, $this->level);
    }
}