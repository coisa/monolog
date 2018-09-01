<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\Factory
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Container\Initializer;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class LoggerAwareInitializer
 *
 * @package CoiSA\Monolog\Container\Initializer
 */
class LoggerAwareInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     * @param mixed $instance
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof LoggerAwareInterface) {
            try {
                $logger = $container->get(LoggerInterface::class);
                $instance->setLogger($logger);
            } catch (ContainerExceptionInterface $exception) {
                // noop
            }
        }
    }
}
