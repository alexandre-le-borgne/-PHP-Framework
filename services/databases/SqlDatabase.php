<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 13:04
 */
class SqlDatabase implements Database
{
    /**
     * @var $pdo PDO
     */
    private $pdo;

    public function __construct($host, $database, $username, $password = '')
    {
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $username, $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->pdo->query("SET NAMES 'utf8'");
    }

    public function execute($query, $params = null, $entity = null)
    {
        try
        {
            if ($params)
            {
                if (!is_array($params))
                    throw new InvalidArgumentException("Parameters should be an array");

                $result = $this->pdo->prepare($query);
                $result->execute($params);
            }
            else
            {
                $result = $this->pdo->query($query);
            }
            if ($entity)
            {
                $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $entity);
            }
        } catch (PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result->fetchAll();
    }


    function select($table, $fields = array(), $entity = null)
    {
        if(count($fields)) {
            $query = 'SELECT * FROM ' . $table . ' WHERE ' . implode(' = ?, ', array_keys($fields)) . ' = ?';
            return $this->execute($query, array_values($fields), $entity);
        }
        else {
            $query = 'SELECT * FROM ' . $table;
            return $this->execute($query, [], $entity);
        }
    }

    public function insert($table, $fields)
    {
        $keys = array_keys($fields);
        $values = '';
        for ($i = 0; $i < count($keys); ++$i)
            $values .= '?, ';
        $values = substr($values, 0, -2);
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES (' . $values . ')';
        $this->execute($query, array_values($fields));
    }

    public function update($table, $fields)
    {
        if(!is_array($fields) && !count($fields)) return;
        $query = 'UPDATE ' . $table . ' SET ' . implode(' = ?, ', array_keys($fields)) . ' = ? WHERE id = ?';
        $this->execute($query, array_values($fields));
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}