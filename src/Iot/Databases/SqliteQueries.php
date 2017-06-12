<?php

namespace Croak\Iot\Databases;


class SqliteQueries
{
    const ADD_MEASURE = "INSERT INTO measures (idDevice, type, unit, value, created) VALUES (:idDevice, :type, :unit, :value, :created)";
    
    const CREATE_TABLE_MEASURES = "CREATE TABLE IF NOT EXISTS measures (
                                    id            INTEGER         PRIMARY KEY AUTOINCREMENT,
                                    idDevice     TEXT,
                                    type          TEXT,
                                    unit          TEXT,
                                    value         REAL,
                                    created       TEXT,
                                    UNIQUE(id)
                                );";
    
    const CREATE_TABLE_USERS = "CREATE TABLE IF NOT EXISTS users (
                                    id            INTEGER         PRIMARY KEY AUTOINCREMENT,
                                    firstname     TEXT,
                                    lastname      TEXT,
                                    email         TEXT            NOT NULL,
                                    keyword       TEXT            NOT NULL,
                                    birthdate     TEXT,
                                    weight        REAL,
                                    size          INTEGER,
                                    address       TEXT,
                                    phoneNumber   TEXT,
                                    comments      TEXT,
                                    created       TEXT,
                                    UNIQUE(id, email)
                            );";

    const CREATE_TABLE_DEVICES = "CREATE TABLE IF NOT EXISTS devices (
                                    id            INTEGER         PRIMARY KEY AUTOINCREMENT,
                                    sn            TEXT,
                                    created       TEXT,
                                    idUser       INTEGER,
                                    UNIQUE(id, sn)
                                );";

    const GET_DEVICE_BY_SN = "SELECT * FROM DEVICES WHERE sn = :sn";

    const ADD_DEVICE = "INSERT OR IGNORE INTO DEVICES (sn, created) VALUES (:sn, :created)";


    private function __construct($data, $id){}
}