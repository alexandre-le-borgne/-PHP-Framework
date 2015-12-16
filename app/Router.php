<?php

class Router {

    private $table = array();

    public function __construct() {

        //"exampleroute" is the name of the route, e.g. /exampleroute
        //Here, class names are used rather than instances so instances are only ever created when needed, otherwise every model, view and
        //controller in the system would be instantiated on every request, which obviously isn't good!

        $this->table['index'] = new Route('IndexModel', 'IndexView', 'IndexController');

    }

    public function getRoute($route) {
        $route = strtolower($route);
        if(isset($this->table[$route]))
            return $this->table[$route];
        return $this->table['index'];
    }

}