<?php

/**
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Handler;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class ErrorLogHandlerFactory
 *
 * @package CoiSA\Monolog\Handler
 */
final class ErrorLogHandlerFactory
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
