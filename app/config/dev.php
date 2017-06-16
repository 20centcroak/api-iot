<?php
$params['displayErrorDetails'] = true;
$params['addContentLengthHeader'] = false;
$params['logger'] = [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '../logs/app.log',
        ];