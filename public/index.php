<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new App\Kernel\Kernel();

$kernel->addConfigFile('routes', __DIR__ . '/../config/routes/routes.php');

$kernel->run();