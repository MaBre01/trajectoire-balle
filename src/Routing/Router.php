<?php

namespace App\Routing;

use App\Kernel\ConfigFile;

class Router
{
    private string $url;
    private array $routes = [];
    private array $namedRoutes = [];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function loadFile(ConfigFile $configFile)
    {
        $routes = $configFile->getFile();

        foreach ($routes as $name => $route) {
            $method = $route['method'];

            if (!method_exists($this, $method)) {
                throw new RouterException("Method $method in route '$name' does not exist");
            }

            $createdRoute = $this->$method($route['path'], $route['action'], $name);

            if (isset($route['params'])) {
                foreach ($route['params'] as $param => $regex) {
                    $createdRoute->with($param, $regex);
                }
            }
        }
    }

    public function add(string $path, $callable, ?string $name = null, string $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if (null !== $name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function get(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    public function url(string $name, array $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
    }
}