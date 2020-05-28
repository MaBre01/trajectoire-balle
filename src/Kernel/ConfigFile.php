<?php

namespace App\Kernel;

class ConfigFile
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getFile()
    {
        return include $this->path;
    }
}