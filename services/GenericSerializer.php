<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 22:04
 */
class GenericSerializer
{

    public function snakeToCamel($val, $prefix = null)
    {
        $val = str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
        if ($prefix)
        {
            $val = $prefix . $val;
        }
        else
        {
            $val = strtolower(substr($val, 0, 1)) . substr($val, 1);
        }
        return $val;
    }

    public function camelToSnake($val, $cut = 0)
    {
        $val = substr($val, $cut);
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $val, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match)
        {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    private function serializeClass($class)
    {
        $reflectionClass = new \ReflectionClass($class);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $data = array();
        foreach ($methods as $method)
        {
            if (empty($method->getParameters()))
            {
                $methodName = $method->getName();
                if (substr($methodName, 0, 3) == 'get')
                {
                    $data[$this->reverseSnakeToCamel($methodName)] = $class->$methodName();
                }
            }
        }
        return $data;
    }

    public function serialize($class, $fields)
    {
        if (!is_object($class)) return $class;

        $reflectionClass = new \ReflectionClass($class);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodsName = array();
        $data = array();
        foreach ($methods as $method)
        {
            $methodsName[] = $method->getName();
        }
        foreach ($fields as $field)
        {
            $field = trim($field);
            $name = $this->snakeToCamel($field, 'get');
            if (in_array($name, $methodsName))
            {
                $return = $class->$name();
                if (is_object($return))
                {
                    $reflectionClass = new \ReflectionClass($return);
                    if ($reflectionClass->getShortName() == 'DateTime')
                    {
                        $data[$field] = $return->format('Y-m-d H:i:s');
                    }
                    else
                    {
                        $data[$field] = $this->serializeClass($return);
                    }
                }
                else
                {
                    $data[$field] = $return;
                }
            }
        }
        return $data;
    }
}