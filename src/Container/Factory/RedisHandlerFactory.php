<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
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
     * @const string Default redis key to store logs
     */
    const DEFAULT_KEY = 'monolog';

    /**
     * @var string Redis key to store logs
     */
    private $key;

    /**
     * RedisHandlerFactory constructor.
     *
     * @param string $key optional Redis key to store the logs
     */
    public function __construct(string $key = null)
    {
        $this->key = $key ?: self::DEFAULT_KEY;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container) : HandlerInterface
    {
        if (!class_exists('\Redis')) {
            return new NullHandler();
        }

        try {
            $redis = $container->get(\Redis::class);
        } catch (ContainerExceptionInterface $exception) {
            // Prevent application crashes
            return new NullHandler();
        }

        return new RedisHandler($redis, $this->key);
    }
}