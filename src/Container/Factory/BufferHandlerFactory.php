<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class BufferHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class BufferHandlerFactory
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
     * @return BufferHandler
     */
    public function __invoke(ContainerInterface $container): BufferHandler
    {
        try {
            $handler = $container->get($this->handler);
        } catch (ContainerExceptionInterface $exception) {
            $handler = new NullHandler();
        }

        return new BufferHandler($handler, null, $this->level);
    }
}