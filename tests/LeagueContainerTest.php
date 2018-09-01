<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Test
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Test;

use CoiSA\Monolog\Container\ServiceProvider\LeagueServiceProvider;
use League\Container\Container;
use Psr\Container\ContainerInterface;

/**
 * Class LeagueContainerTest
 *
 * @package CoiSA\Monolog\Test
 */
class LeagueContainerTest extends ContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $container = new Container();
        $container->addServiceProvider(new LeagueServiceProvider());

        return $container;
    }
}
