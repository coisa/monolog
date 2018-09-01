<?php
/**
 * @author Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @package CoiSA\Monolog\Container\ConfigProvider
 */

declare(strict_types=1);

namespace CoiSA\Monolog\Test;

use CoiSA\Monolog\Container\ServiceProvider\PimpleServiceProvider;
use Pimple\Container;
use Psr\Container\ContainerInterface;

/**
 * Class PimpleTest
 *
 * @package CoiSA\Monolog\Test
 */
class PimpleTest extends ContainerTest
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        $pimple = new Container();
        $pimple->register(new PimpleServiceProvider());

        return new \Pimple\Psr11\Container($pimple);
    }
}
