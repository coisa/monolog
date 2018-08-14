<?php

use CoiSA\Monolog\Container\ServiceProvider\PimpleServiceProvider;
use Pimple\Container;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$pimple = new Container();
$pimple->register(new PimpleServiceProvider());

return $pimple['logger'];