<?php

require_once '../vendor/autoload.php';
require_once '../app/config/dev.php';
require_once '../app/app.php';

use Croak\Iot\Init\Build;
use Croak\Iot\Exceptions\InitException;
use Croak\Iot\Exceptions\BuildException;


//defines a logger for the app
$logger = $app->getContainer()->get('logger');

// //get app configuration
// $config = new Config();
// try{
//     $config->readConfigFile();
// }
// catch(InitException $ie){
//      $logger->addInfo($ie->getMessage());

//     if ($ie->getMessage()===InitException::CONFIG_FILE_NOT_FOUND){
//         //config file does not exist, create the config file 
//         //with a default set of parameters
//         try{
//             Build::createInitFile($config);
//         }
//         catch(BuildException $be){
//             $logger->addInfo($be->getMessage());
//             return $be->getMessage();
//         }
//         catch(InitException $e){
//             $logger->addInfo($e->getMessage());
//             return $e->getMessage();
//         }
//     }

//     //build the app: create database and tables
//     try{
//         Build::build($config);
//     }
//     catch(BuildException $be){
//         $logger->addInfo($be->getMessage());
//         return $be->getMessage();
//     }
    
// }

$logger->addInfo("init ok");

//define routes
require_once '../app/routes.php';

//launch app
$app->run();