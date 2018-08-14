<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class ErrorLogHandlerFactory
 *
 * @package CoiSA\Monolog\Container\Factory
 */
class ErrorLogHandlerFactory
{
    /**
     * @var int Log level
     */
    private $level;

    /**
     * ErrorLogHandlerFactory constructor.
     *
     * @param int $level
     */
    public function __construct(int $level = Logger::ERROR)
    {
        $this->level = $level;
    }

    /**
     * Syslog handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return ErrorLogHandler
     */
    public function __invoke(ContainerInterface $container): ErrorLogHandler
    {
        return new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $this->level);
    }
}