<?php

class Router {

    private $table = array();

    public function __construct() {

        //"exampleroute" is the name of the route, e.g. /exampleroute
        //Here, class names are used rather than instances so instances are only ever created when needed, otherwise every model, view and
        //controller in the system would be instantiated on every request, which obviously isn't good!
        // Route(routename, controllername, actionname);
        $this->table[] = $this->getDefaultRoute();
        $this->table[] = new Route('login', 'user', 'login');
        $this->table[] = new Route('preregister', 'user', 'preRegister');
        $this->table[] = new Route('register', 'user', 'register');
        $this->table[] = new Route('testfeed', 'index', 'feed');
        $this->table[] = new Route('mailvalidation', 'user', 'mailValidation');
        $this->table[] = new Route('exemple', 'exemple', 'index');
        $this->table[] = new Route('twitter', 'testtwitter', 'twitter');

    }

    public function getRoute($name)
    {
        $name = strtolower($name);
        foreach ($this->table as $route)
            if ($route->getName() == $name)
                return $route;
        return $this->getDefaultRoute();
    }

    public function getDefaultRoute() {
        return new Route('index', 'index', 'index');
    }
}