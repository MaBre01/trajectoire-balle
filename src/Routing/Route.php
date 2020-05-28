<?php

namespace App\Routing;

class Route
{
    private const CONTROLLER_NAMESPACE = 'App\\Controller\\';
    private const CONTROLLER_SUFFIX = 'Controller';

    private string $path;
    private $callable;
    private array $matches;
    private array $params;

    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;

        $this->matches = [];
        $this->params = [];
    }

    public function with(string $param, string $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function match(string $url): bool
    {
        $allUrl = trim($url, '/');
        $url = explode('?', $allUrl)[0];
        
        $pathRegex = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);

        $regex = "#^$pathRegex$#i";

        $matches = null;
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;

        return true;
    }

    private function paramMatch(array $match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('::', $this->callable);
            $controller = self::CONTROLLER_NAMESPACE . $params[0] . self::CONTROLLER_SUFFIX;
            $controller = new $controller();

            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function getUrl(array $params)
    {
        $path = $this->path;

        foreach ($params as $key => $value) {
            $path = str_replace(":$key", $value, $path);
        }

        return $path;
    }
}