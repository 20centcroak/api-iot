<?php

require_once '../vendor/autoload.php';
require_once '../app/config/dev.php';
require_once '../app/app.php';

use Croak\Iot\Init\Build;
use Croak\Iot\Exceptions\InitException;
use Croak\Iot\Exceptions\BuildException;
use Croak\Iot\Init\Config;

$logger = $app->getContainer()->get('logger');
$config = new Config();

try{
    $config->readConfigFile();
}
catch(InitException $ie){
     $logger->addInfo($ie->getMessage());

    if ($ie->getMessage()===InitException::CONFIG_FILE_NOT_FOUND){
        try{
            Build::createInitFile($config);
        }
        catch(BuildException $be){
            $logger->addInfo($be->getMessage());
            return;
        }
    }

    try{
        Build::build($config);
    }
    catch(BuildException $be){
        $logger->addInfo($be->getMessage());
        return;
    }
    
}

$logger->addInfo("init ok");

require_once '../app/routes.php';

$app->run();