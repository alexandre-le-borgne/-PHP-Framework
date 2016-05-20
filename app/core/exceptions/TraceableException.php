<?php

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
        $trace = array_reverse($trace);
        array_shift($trace);
        array_pop($trace);
        $length = count($trace);
        $result = array();
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
            'trace' => $this->generateCallTrace()
        );
    }
}