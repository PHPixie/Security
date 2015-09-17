<?php

namespace PHPixie\Security;

class Password
{
    public function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}