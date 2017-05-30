<?php

require_once '../vendor/autoload.php';
require_once '../app/config/dev.php';
require_once '../app/app.php';

use Croak\Iot\Init\Build;

$app = new \Slim\App(["settings" => $config]);

if (!Build::isBuilt()){
    $app->logger->addInfo("init failed");
    return;
}
$app->logger->addInfo("init ok");



require_once '../app/routes.php';

$app->run();