<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
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
     * Stream handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = new StreamHandler('php://stdout', Logger::INFO);
        } catch (\Exception $exception) {
            $handler = new NullHandler();
        }

        return $handler;
    }
}
