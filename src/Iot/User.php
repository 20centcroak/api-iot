<?php

namespace Croak\Iot;
use Croak\DbManagement\Exceptions\IotException;
use Croak\DbManagement\DbManagementObject;

/**
 * Describes a User thanks to a set of parameters
 * a user is described by its :
 * -name 
 * -email 
 * -password
 * -caracteristics
 */
class User extends DbManagementObject{

    /**
    *@var Array  KEYS  key names expected in params file
    */
    const KEYS = array(
        "firstName"=>"first",
        "lastName"=>"last",
        "email"=>"email",
        "password"=>"password",
        "birthday"=>"birthday",
        "gender"=>"gender",
        "size"=>"size",
        "weight"=>"weight",
        "address"=>"address",
        "phoneNumber"=>"phone",
        "comments"=>"comments",
        "date"=>"created"
    );

    /**
    *@var Array  KEY_TYPES  key types associated with key names
    */
    const KEY_TYPES = array(
        "firstName"=>"is_string",
        "lastName"=>"is_string",
        "email"=>"is_string",
        "password"=>"is_string",
        "birthday"=>"is_string",
        "gender"=>"is_string",
        "size"=>"is_numeric",
        "weight"=>"is_numeric",
        "address"=>"is_string",
        "phoneNumber"=>"is_string",
        "comments"=>"is_string",
        "date"=>"is_string"
    );

    /**
    *@var Array  KEY_REQUIRED  indicates which keys are required
    */
    const KEY_REQUIRED = array(
        "firstName"=>false,
        "lastName"=>false,
        "email"=>true,
        "password"=>true,
        "birthday"=>false,
        "gender"=>false,
        "size"=>false,
        "weight"=>false,
        "address"=>false,
        "phoneNumber"=>false,
        "comments"=>false,
        "date"=>false
    );

    /**
    *@var Array  KEY_UNIQUE  indicates which keys should be unique
    */
    const KEY_UNIQUE = array(
        "firstName"=>false,
        "lastName"=>false,
        "email"=>true,
        "password"=>false,
        "birthday"=>false,
        "gender"=>false,
        "size"=>false,
        "weight"=>false,
        "address"=>false,
        "phoneNumber"=>false,
        "comments"=>false,
        "date"=>false
    );
 
    /**
    * getter of keys defining the IotObject
    * These keys should be defined as a constant of the IotObject
    * @return array of String 
    */
    public function getKeys(){
        return User::KEYS;
    }
    
    /**
    * getter of Types associated with the keys defining the IotObject
    * types are test function names like is_string, is_float, is_int, is_numeric, ...
    * @return array of String 
    */
    public function getTypes(){
        return User::KEY_TYPES;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key is required (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getRequiredKeys(){
        return User::KEY_REQUIRED;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key should be unique (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getUniqueKeys(){
        return User::KEY_UNIQUE;
    }
}