<?php

namespace Croak\Iot\Init;
use Croak\Iot\Exceptions\InitException;

/**
 * Manages the app parameters contained in the api-config.json file
 */
class Config
{
    const FILENAME = "../api-config.json";
    const INIT_KEY = "init";
    const SN_PATTERN = "snPattern";

    private $init;
    private $snPattern;

    /**
    * the Object is built whithout any parameters
    */
    public function __construct() { }

    /**
     * read the configuration file and extract the parameters to be used by the app
     *
     * @throws InitException        when an error occured when reading the configuration file
     */
    public function readConfigFile()
    {
        if(!file_exists(Config::FILENAME)){
            throw new InitException(InitException::CONFIG_FILE_NOT_FOUND);
        }

        $json = file_get_contents(Config::FILENAME);
        if($json===false){
            throw new InitException(InitException::CONFIG_FILE_ERROR);
        }

        $config = json_decode($json, true);

        $this->init = $config[Config::INIT_KEY];
        $this->snPattern = $config[Config::SN_PATTERN];

        if($this->init===false){
            throw new InitException(InitException::CONFIG_NOT_INITIALISED);
        }
    }

    /**
    * define a default set of parameters for the configuration file
    */
    public function setDefault()
    {
        $this->init = false;
        $this->snPattern = "/[0-9]{0,5}/";
    }

    /**
    * use the current parameters to update the configuration file. 
    * parameters could have been set thanks to the setter functions of the Class
    * @throws InitException        when an error occured when writing in the configuration file
    */
    public function updateFile()
    {        
        $json =  $this->getJson();
        $file = fopen(Config::FILENAME, 'w');
        if($file===false){
            throw new InitException(InitException::CONFIG_FILE_CREATION_FAILED);
        }
        $bytes = fwrite($file, $json);
        if($bytes===false){
            throw new InitException(InitException::CONFIG_FILE_CREATION_FAILED);
        }
        fclose($file);
    }

    /**
    * make a Json String from the parameters of the Object
    * @return json      the json String containing the set of config parameters
    */
    public function getJson()
    {
        $array=array(
            Config::INIT_KEY=>$this->init,
            Config::SN_PATTERN=>$this->snPattern
        );

        return json_encode($array);
    }

    /**
    * set the init flag to true
    */
    public function setInit()
    {
        $this->init = true;
    }

    /**
    * check if the init flag is true
    * @return boolean       true if init flag is true, false otherwise
    */
    public function isInit()
    {
        return $this->init;
    }

    /**
    * set the sn pattern. The device serial number should match this specific pattern
    * @param regex  a regex String defining the sn pattern
    */
    public function setSnPattern($pattern)
    {
        $this->snPattern = $pattern;
    }

    /**
    * get the sn pattern
    * @return regex String     the regex String defining the device serial number format
    */
    public function getSnPattern()
    {
        return $this->snPattern;
    }
    
}