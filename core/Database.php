<?php

interface Database
{
    function execute($query, $params = null, $entity = null);
    function insert($table, $fields);
    function update($table, $fields);
    function lastInsertId();
    function getDateFormat();
}