<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\SyslogHandler;
use Psr\Container\ContainerInterface;

/**
 * Class SyslogHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class SyslogHandlerFactory
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
     * Syslog handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return SyslogHandler
     */
    public function __invoke(ContainerInterface $container): SyslogHandler
    {
        return new SyslogHandler($this->identity);
    }
}