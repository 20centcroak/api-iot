<?php

namespace Croak\Iot\Databases;
use Croak\Iot\Exception\DataBaseException;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\SqliteQueries;
use Croak\Iot\Measure as Measure;

/**
 * Manages the table containing the measures
 */
class TableMeasures
{
    /**
    *@var Measure       measure parameters
    */
    private $measure;

    /**
     * construct the object thanks to the definition of a measure object
     * @param string $measure        the measure object defining a measurement
     */
    public function __construct(Measure $measure)
    {
        $this->measure = $measure;
    }

    /**
     * add a measure to the measure table in the database
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @throws DataBaseException     error in connecting to the database
     * @return id of the record
     */
    public function populate(DbManagement $db)
    {
        $array = array(
            Measure::ID_DEVICE_KEY		=> $this->measure->getIdDevice(),
            Measure::TYPE_KEY			=> $this->measure->getType(),
            Measure::UNIT_KEY           => $this->measure->getUnit(),
            Measure::VALUE_KEY			=> $this->measure->getValue(),
            Measure::DATE_KEY		    => $this->measure->getDate()
        );

        $db->query(SqliteQueries::ADD_MEASURE, $array);
        return $db->lastInsertId();
    }
}