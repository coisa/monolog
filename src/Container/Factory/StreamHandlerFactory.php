<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class StreamHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class StreamHandlerFactory
{
    /**
     * @var string Stream path
     */
    private $path;

    /**
     * @var int Log level
     */
    private $level;

    /**
     * StreamHandlerFactory constructor.
     *
     * @param string $path Resource system path for the handler
     * @param int $level optional Log level for the handler
     */
    public function __construct(string $path = 'php://stdout', int $level = Logger::DEBUG)
    {
        $this->path = $path;
        $this->level = $level;
    }

    /**
     * Stream handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = new StreamHandler($this->path, $this->level);
        } catch (\Exception $exception) {
            // Prevent application crashes
            $handler = new NullHandler();
        }

        return $handler;
    }
}