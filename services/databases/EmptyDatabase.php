<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 21:27
 */
class EmptyDatabase implements Database
{
    function execute($query, $params = null, $entity = null)
    {
        throw new LogicException();
    }

    function lastInsertId()
    {
        throw new LogicException();
    }

    function select($table, $fields = array(), $entity = null)
    {
        throw new LogicException();
    }
    
    function insert($table, $fields)
    {
        throw new LogicException();
    }

    function update($table, $fields)
    {
        throw new LogicException();
    }

    public function getDateFormat()
    {
        throw new LogicException();
    }
    
}