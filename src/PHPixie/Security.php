<?php

namespace PHPixie;

class Security
{
    protected $builder;
    
    public function __construct($database)
    {
        $this->builder = $this->buildBuilder($database);
    }
    
    public function password()
    {
        return $this->builder->password();
    }
    
    public function random()
    {
        return $this->builder->random();
    }
    
    public function tokens()
    {
        return $this->builder->tokens();
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder($database)
    {
        return new Security\Builder($database);
    }
}