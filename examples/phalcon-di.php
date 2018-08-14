<?php

use CoiSA\Monolog\Container\ServiceProvider\PhalconServiceProvider;
use Phalcon\Di;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$di = new Di();
$di->register(new PhalconServiceProvider());

return $di->getShared('logger');