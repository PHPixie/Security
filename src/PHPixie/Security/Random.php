<?php

namespace PHPixie\Security;

class Random
{
    public function string($length, $alphabet='0123456789abcdef')
    {
        $buf = '';
        $l = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $buf .= $alphabet[random_int(0, $l)];
        }
        return $buf;
    }
    
    public function bytes($length)
    {
        return random_bytes($length);
    }
}
