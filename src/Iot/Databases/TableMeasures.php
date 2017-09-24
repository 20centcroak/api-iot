<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\SqliteQueries;
use Croak\Iot\Measure;

/**
 * Manages the table containing the measures
 */
class TableMeasures
{

    /**
     * construct the object thanks to the definition of a measure object
     * @param string $measure        the measure object defining a measurement
     */
    public function __construct()
    {
    }

    /**
     * add a measure to the measure table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @throws DataBaseException     error in connecting to the database
     * @return id of the record
     */
    public function populate(DbManagement $db, MEASURE $measure)
    {
        $array = $measure->getValues();
        $db->query(SqliteQueries::ADD_MEASURE, $array);
        return $db->lastInsertId();
    }

    public function getMeasures(DbManagement $db, $sn, $params){
        $db->query(SqliteQueries::GET_MEASURE_BY_SN, $array);
    }
}