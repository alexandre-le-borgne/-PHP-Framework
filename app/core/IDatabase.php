<?php

/**
 * Interface IDatabase
 */
interface IDatabase
{
    /**
     * @param string $query
     * @param array $params
     * @param string $entity
     * @return mixed
     */
    function execute($query, $params = null, $entity = null);

    /**
     * If third parameter $entity is precised, it return array of Entity
     * @param string $table
     * @param array $fields
     * @param string $entity
     * @return array
     */
    function select($table, $fields = array(), $entity = null);

    /**
     * @param string $table
     * @param array $fields
     */
    function insert($table, $fields);

    /**
     * @param string $table
     * @param array $fields
     */
    function update($table, $fields);

    /**
     * @return int
     */
    function lastInsertId();

    /**
     * @return string
     */
    function getDateFormat();
}