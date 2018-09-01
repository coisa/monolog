<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\RedisHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class RedisHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class RedisHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        if (!class_exists(\Redis::class)) {
            return new NullHandler();
        }

        try {
            $redis = $container->get(\Redis::class);
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new RedisHandler($redis, 'monolog');
    }
}
