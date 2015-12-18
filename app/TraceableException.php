<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/12/2015
 * Time: 08:33
 */
class TraceableException extends Exception
{
    public function generateCallTrace()
    {
        $trace = explode("\n", $this->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();
        // replace '#someNum' with '$i)', set the right ordering
        for ($i = 0; $i < $length; $i++) {
            $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' '))."<br>";
        }

        return "\t" . implode("\n\t", $result);
    }
}