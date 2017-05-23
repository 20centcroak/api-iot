<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['logger'] = [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '../logs/app.log',
        ];



$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$app->get('/profile/{sn}', function (Request $request, Response $response, $args) {

	$profile_sn = (string)$args['sn'];
    $this->logger->addInfo("get profile for ".$profile_sn);
    // $log = $this->get('settings')['logger'];
    // $log->addInfo("get profile for ".$profile_sn);

    $filename = "../register/".$profile_sn;
    $this->logger->addInfo("looking for file ".$filename);
    // $log = $this->get('settings')['logger'];
    // $log->addInfo("looking for file ".$filename);

    if(!file_exists($filename)) {
        $response->getBody()->write("unknown device");
        return $response;
    }

    $response->getBody()->write("Hi ".$profile_sn." !");

    return $response;
});


$app->put('/add/{sn}', function ($request, $response, $args) {
    
    $id = (string)$args['sn'];
    $this->logger->addInfo("put infos for ".$id);    
    
    $filename = "../register/".$id."/temp.txt";

    if(!file_exists($filename)) {
        $response->getBody()->write("unknown device");
        return $response;
    }

    $temp = fopen($filename,"a") or die("Unable to open file!");

    $this->logger->addInfo("file open successfully"); 

    $dat = $request->getBody();
    $this->logger->addInfo("dat = ".$dat);
    $data = json_decode($dat);

    $mesure = $data->{'temp'};
    $this->logger->addInfo("mesure = ".$mesure); 
    // $value = $mesure['temp'];
    // $this->logger->addInfo("value = ".$value); 
    fwrite($temp,$mesure."\n");
    fclose($temp);

});

$app->run();