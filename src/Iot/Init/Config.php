<?php

namespace Croak\Iot\Init;
use Croak\Iot\Exceptions\InitException;

class Config
{
    const FILENAME = "../api-config.json";
    const INIT_KEY = "init";
    const SN_PATTERN = "snPattern";

    private $init;
    private $snPattern;

    private function __construct($config)
    {
        $this->init = $config[Config::INIT_KEY];
        $this->snPattern = $config[Config::SN_PATTERN];
    }

    public static function readConfigFile()
    {
        if(!file_exists(Config::FILENAME)){
            throw new InitException(InitException::CONFIG_FILE_NOT_FOUND);
        }

        $json = file_get_contents(Config::FILENAME);
        if($json===false){
            throw new InitException(InitException::CONFIG_FILE_ERROR);
        }

        $config = json_decode($json, true);

        if(!$config[Config::INIT_KEY]===true){
            throw new InitException(InitException::CONFIG_NOT_INITIALISED);
        }

        return new Config($config);
    }

    public static function setDefault(){
        $config = array();
        $config[Config::INIT_KEY] = true;;
        $config[Config::SN_PATTERN] = "[0-9]{0,5}";
        return new Config($config);
    }

    public function updateFile($json = getJson())
    {        
        $file = fopen(Config::FILENAME, 'w');
        if($file===false){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }
        $bytes = fwrite($file, $json);
        if($bytes===false){
            throw new BuildException(BuildException::CONFIG_FILE_CREATION_FAILED);
        }
        fclose($file);
    }

    public function getJson()
    {
        $array=array(
            Config::INIT_KEY=>$this->init,
            Config::SN_PATTERN=>$this->snPattern
        );

        return json_encode($array);
    }

    public function setInit($initValue)
    {
        $this->init = $initValue;
    }
    public function getInit()
    {
        return $this->init;
    }

    public function setSnPattern($pattern)
    {
        $this->snPattern = $pattern;
    }
    public function getSnPattern()
    {
        return $this->snPattern;
    }
    
}