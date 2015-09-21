<?php

namespace PHPixie\Security;

class Random
{
    protected $characters;
    
    public function string($length)
    {
        $characters = $this->characters();
        
        $string = '';
        $max = count($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $string.= $characters[random_int(0, $max)];
        }
        
        return $string;
    }
    
    public function bytes($length)
    {
        return random_bytes($length);
    }
    
    protected function characters()
    {
        if($this->characters === null) {
            $this->characters = $this->getCharacters();
        }
        
        return $this->characters;
    }
    
    protected function getCharacters()
    {
        return str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
}
