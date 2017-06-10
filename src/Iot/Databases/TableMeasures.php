<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\SqliteQueries;

class TableMeasures
{
    private $measure;

    public function __construct($measure)
    {
        $this->measure = $measure;
    }

    public function populate()
    {
        $array = array(
            Measure::ID_DEVICE_KEY		=> $this->measure->getIdDevice(),
            Measure::TYPE_KEY			=> $this->measure->getType(),
            Measure::UNIT_KEY           => $this->measure->getUnit(),
            Measure::VALUE_KEY			=> $this->measure->getValue(),
            Measure::DATE_KEY		    => $this->measure->getDate()
        );

        try{
            $db = DbManagement::connect();
            $queryOk = $db->query(SqliteQueries::ADD_MEASURE, $array);
            if($queryOk===false){
                throw new DataBaseException(DataBaseException::ADD_FAILED);
            }            
        }
        catch(DataBaseException $e){
            throw new DataBaseException(DataBaseException::DB_CONNECTION_FAILED);
        }
        $db->disconnect();
    }
}