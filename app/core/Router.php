<?php

/**
 * Class Router
 */
final class Router
{
    /**
     * @var array
     */
    private $table = array();

    /**
     * Router constructor.
     * @param array $routes
     */
    public function __construct($routes)
    {
        $this->table = $routes;
        if (!array_key_exists('index', $this->table))
            $this->table[] = $this->getDefaultRoute();
    }

    /**
     * @param string $name
     * @return Route
     */
    public function getRoute($name)
    {
        $name = strtolower($name);
        foreach ($this->table as $route)
            if ($route->getName() == $name)
                return $route;
        return $this->getDefaultRoute();
    }

    /**
     * @return Route
     */
    public function getDefaultRoute()
    {
        return new Route('index', 'index', 'index');
    }
}