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

    public function connect($host, $database, $username, $password = '')
    {
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname' . $database . ';charset=utf8', $username, $password,
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
                    throw new Exception("Parameters should be an array");

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
        return $result;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}