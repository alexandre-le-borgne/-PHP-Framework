<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 20/05/2016
 * Time: 17:52
 */
class Response
{
    private $controller;
    private $method;
    private $response;

    public function __construct($controller, $method, $response)
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        if(!$this->response)
            throw new BadMethodCallException("Action '".ucfirst($this->method)."' of the controller '".ucfirst($this->controller)."' must return a response");
        return $this->response;
    }
}