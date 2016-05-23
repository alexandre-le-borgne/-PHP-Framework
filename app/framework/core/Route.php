<?php

/**
 * Class Route
 */
final class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * Route constructor.
     * @param string $name
     * @param string $controller
     * @param string $action
     */
    public function __construct($name, $controller, $action)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return ucfirst($this->controller . 'Controller');
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return ucfirst($this->action . 'Action');
    }
}