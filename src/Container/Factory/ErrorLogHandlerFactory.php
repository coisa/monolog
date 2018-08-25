<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Factory;

use Monolog\Handler\ErrorLogHandler;
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
     * Error log handler service factory
     *
     * @param ContainerInterface $container
     *
     * @return ErrorLogHandler
     */
    public function __invoke(ContainerInterface $container): ErrorLogHandler
    {
        return new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::ERROR);
    }
}
