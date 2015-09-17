<?php

namespace PHPixie\Security\Tokens;

interface Storage
{
    public function insert($token);
    public function get($series);
    public function update($token);
    public function remove($series);
}