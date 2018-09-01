<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\GroupHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FingersCrossedHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class FingersCrossedHandlerFactory
{
    /**
     * Fingers crossed handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $handler = $container->get(GroupHandler::class);
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new FingersCrossedHandler($handler, Logger::ERROR);
    }
}
