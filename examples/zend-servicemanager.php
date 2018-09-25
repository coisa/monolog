<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

use CoiSA\Monolog\ConfigProvider;
use Zend\ServiceManager\ServiceManager;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$configProvider = new ConfigProvider();

$serviceManager = new ServiceManager();
$serviceManager->configure($configProvider->getDependencies());

$serviceManager->setService('config', $configProvider());

return $serviceManager->get('logger');
