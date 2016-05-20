<?php

class Router
{

    private $table = array();

    public function __construct($routes)
    {
        $this->table = $routes;
        if(!array_key_exists('index', $this->table))
            $this->table[] = $this->getDefaultRoute();
    }

    public function getRoute($name)
    {
        $name = strtolower($name);
        foreach ($this->table as $route)
            if ($route->getName() == $name)
                return $route;
        return $this->getDefaultRoute();
    }

    public function getDefaultRoute()
    {
        return new Route('index', 'index', 'index');
    }
}