<?php

use CoiSA\Monolog\ConfigProvider;
use Zend\ServiceManager\ServiceManager;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$configProvider = new ConfigProvider();

$serviceManager = new ServiceManager();
$serviceManager->configure($configProvider->getDependencies());

$serviceManager->setService('config', $configProvider());

return $serviceManager->get('logger');
