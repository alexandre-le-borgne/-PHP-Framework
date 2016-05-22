<?php

/**
 * Class Response
 */
class Response
{
    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $response;

    /**
     * Response constructor.
     * @param string $controller
     * @param string $method
     * @param string $response
     */
    public function __construct($controller, $method, $response)
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        if (!$this->response)
            throw new BadMethodCallException("Action '" . ucfirst($this->method) . "' of the controller '" . ucfirst($this->controller) . "' must return a response");
        return $this->response;
    }
}