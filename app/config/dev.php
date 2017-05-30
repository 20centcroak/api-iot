<?php
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['logger'] = [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '../logs/app.log',
        ];