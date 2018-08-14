<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\RavenHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class RavenHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class RavenHandlerFactory
{
    /**
     * @var string Syslog identity name
     */
    private $identity;

    /**
     * SyslogHandlerFactory constructor.
     *
     * @param string|null $identity
     */
    public function __construct(string $identity = null)
    {
        $this->identity = $identity ?: gethostname();
    }

    /**
     * Raven handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return HandlerInterface
     */
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        try {
            $client = $container->get('Raven_Client');
        } catch (ContainerExceptionInterface $exception) {
            return new NullHandler();
        }

        return new RavenHandler($client);
    }
}