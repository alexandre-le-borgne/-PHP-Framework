<?php

interface Database
{
    function connect($host, $database, $username, $password = '');
    function execute($query, $params = null, $entity = null);
    function lastInsertId();
}