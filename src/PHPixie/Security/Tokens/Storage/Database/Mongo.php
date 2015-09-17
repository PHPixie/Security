<?php

namespace PHPixie\Security\Tokens\Storage\Database;

class Mongo extends \PHPixie\Security\Tokens\Storage\Database
{
    protected $collection;
    
    public function __construct($tokens, $connection, $configData)
    {
        parent::__construct($tokens, $connection);
        $this->collection = $configData->getRequired('collection');
    }
    
    protected function setSource($query)
    {
        $query->collection($this->collection);
    }
}