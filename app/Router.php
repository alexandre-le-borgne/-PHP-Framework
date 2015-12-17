<?php

class Router {

    private $table = array();

    public function __construct() {

        //"exampleroute" is the name of the route, e.g. /exampleroute
        //Here, class names are used rather than instances so instances are only ever created when needed, otherwise every model, view and
        //controller in the system would be instantiated on every request, which obviously isn't good!
        // Route(routename, controllername, actionname);
        $this->table[] = $this->getDefaultRoute();
        $this->table[] = new Route('preregister', 'index', 'preregister');
        $this->table[] = new Route('register', 'index', 'register');
    }

    public function getRoute($route)
    {
        $route = strtolower($route);
        foreach ($this->table as $route) {
            echo $route->getName() ."***". $route;
            if ($route->getName() == $route) {
                echo "ROUTE FOUND";
                return $route;
            }
        }
        return $this->getDefaultRoute();
    }

    public function getDefaultRoute() {
        return new Route('index', 'index', 'index');
    }
}