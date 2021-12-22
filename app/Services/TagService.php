<?php

namespace App\Services;

class TagService
{
    public $name;
    
    public function __construct(string $name)
    {
        $this->name = str_replace(' ', '-', strtolower($name));
    }
}
