<?php

//TODO voir pertinence de cela
// exec("composer.phar update", $out, $ret);
//         if(!$ret) {
//             print("update ok");
//         } else {
//            print("update nok");
//         }

require_once '../vendor/autoload.php';
require_once '../app/config/dev.php';
require_once '../app/app.php';

use Croak\Iot\Init\Build;
use Croak\Iot\Exceptions\InitException;
use Croak\Iot\Init\Config;

$logger = $app->getContainer()->get('logger');
$config;

try{
    $config = Config::readConfigFile();
}
catch(InitException $ie){
     $logger->addInfo($ie->getMessage());

    try{
        Build::build();
    }
    catch(BuildException $be){
        $logger->addInfo($be->getMessage());
        return;
    }     
}

$logger->addInfo("init ok");

require_once '../app/routes.php';

$app->run();