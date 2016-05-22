<?php

/**
 * Class EmptyDatabase
 */
final class EmptyDatabase implements IDatabase
{
    /**
     * @param string $query
     * @param array|null $params
     * @param string|null $entity
     * @return mixed|void
     * @throws LogicException
     */
    function execute($query, $params = null, $entity = null)
    {
        throw new LogicException();
    }

    /**
     * @return int
     * @throws LogicException
     */
    function lastInsertId()
    {
        throw new LogicException();
    }

    /**
     * @param string $table
     * @param array $fields
     * @param null $entity
     * @return array|void
     * @throws LogicException
     */
    function select($table, $fields = array(), $entity = null)
    {
        throw new LogicException();
    }

    /**
     * @param string $table
     * @param array $fields
     * @throws LogicException
     */
    function insert($table, $fields)
    {
        throw new LogicException();
    }

    /**
     * @param string $table
     * @param array $fields
     * @throws LogicException
     */
    function update($table, $fields)
    {
        throw new LogicException();
    }

    /**
     * @return string
     * @throws LogicException
     */
    public function getDateFormat()
    {
        throw new LogicException();
    }
}