<?php

namespace App\Kernel;

use App\Routing\Router;

class Kernel
{
    private Router $router;
    private array $configFiles = [];

    public function __construct()
    {
        $this->router = new Router($_SERVER['REQUEST_URI']);
    }

    public function addConfigFile(string $name, string $path)
    {
        $this->configFiles[$name] = new ConfigFile($path);
    }

    public function run()
    {
        if (isset($this->configFiles['routes'])) {
            $this->router->loadFile($this->configFiles['routes']);
        }

        $this->router->run();
    }
}