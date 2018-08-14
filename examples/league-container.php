<?php

use CoiSA\Monolog\Container\ServiceProvider\LeagueServiceProvider;
use League\Container\Container;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = new Container();
$container->addServiceProvider(new LeagueServiceProvider());

return $container->get('logger');