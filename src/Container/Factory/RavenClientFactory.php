<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Psr\Container\ContainerInterface;
use Raven_Client;

/**
 * Class RavenClientFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class RavenClientFactory
{
    /**
     * @var string Sentry DSN
     */
    private $dsn;

    /**
     * RavenClientFactory constructor.
     *
     * @param string $dsn Sentry DSN
     */
    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return Raven_Client
     */
    public function __invoke(ContainerInterface $container): Raven_Client
    {
        return new Raven_Client($this->dsn);
    }
}