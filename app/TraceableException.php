<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/12/2015
 * Time: 08:33
 */

class TraceableException extends Exception
{
    private $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function generateCallTrace()
    {
        $trace = explode("\n", $this->exception->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();
        // replace '#someNum' with '$i)', set the right ordering
        for ($i = 0; $i < $length; $i++)
        {
            $result[$i] = substr($trace[$i], strpos($trace[$i], ' '));
        }
        return $result;
    }

    public function getData()
    {
        return array(
            'code' => $this->exception->getCode(),
            'name' => get_class($this->exception),
            'message' => $this->exception->getMessage(),
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'trace' => $this->exception->generateCallTrace()
        );
    }

    public function show()
    {
        View::getView('layouts/exception', $this->getData());
    }
}