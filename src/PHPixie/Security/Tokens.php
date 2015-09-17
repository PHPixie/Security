<?php

namespace PHPixie\Security;

class Tokens
{
    protected $storageTypes = array(
        'database'
    );
    
    protected $builder;
    protected $database;
    
    public function __construct($builder, $database)
    {
        $this->builder = $builder;
        $this->database = $database;
    }
    
    public function token($series, $userId, $challenge, $expires, $string = null)
    {
        return new Tokens\Token($series, $userId, $challenge, $expires, $string);
    }
    
    public function handler($configData)
    {
        return new Tokens\Handler(
            $this,
            $this->builder->random(),
            $configData
        );
    }
        
    public function sqlStorage($connection, $configData)
    {
        return new Tokens\Storage\Database\SQL(
            $this,
            $connection,
            $configData
        );
    }
    
    public function mongoStorage($connection, $configData)
    {
        return new Tokens\Storage\Database\Mongo(
            $this,
            $connection,
            $configData
        );
    }
    
    public function buildStorage($configData)
    {
        $type = $configData->get('type', 'database');
        if(!in_array($type, $this->storageTypes)) {
            throw new \PHPixie\Security\Exception("Token storage type '$type' does not exist");
        }
        
        $method = $type.'Storage';
        return $this->$method($configData);
    }  
    
    public function databaseStorage($configData)
    {
        $connectionName = $configData->get('connection', 'default');
        $connection = $this->database->get($connectionName);
        
        if($connection instanceof \PHPixie\Database\Type\SQL\Connection) {
            return $this->sqlStorage($connection, $configData);
        }
        
        if($connection instanceof \PHPixie\Database\Driver\Mongo\Connection) {
            return $this->mongoStorage($connection, $configData);
        }
        
        $class = get_class($connection);
        throw new \PHPixie\Security\Exception("No storage for the '$class' connection");
    }
}