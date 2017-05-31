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

$logger =$app->getContainer()->get('logger');

try{
    if(!Build::check()){
        $logger->addInfo("init failed");
        return;
    }
}
catch(InitException $e){
     $logger->addInfo($e->getMessage());
     return;
}

$logger->addInfo("init ok");

require_once '../app/routes.php';

$app->run();