<?php

class Route
{

    private $name;
    private $controller;
    private $action;

    public function __construct($name, $controller, $action)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->action = $action;

    }

    public function getName()
    {

        return $this->name;

    }

    public function getController()
    {

        return ucfirst($this->controller.'Controller');

    }

    public function getAction()
    {

        return ucfirst($this->action.'Action');

    }
}