<?php

/**
 * Class SqlDatabase
 */
class SqlDatabase implements IDatabase
{
    /**
     * @var $pdo PDO
     */
    private $pdo;

    /**
     * SqlDatabase constructor.
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct($host, $database, $username, $password = '')
    {
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $username, $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->pdo->query("SET NAMES 'utf8'");
    }

    /**
     * @param string $query
     * @param array|null $params
     * @param string|null $entity_name
     * @return PDOStatement
     * @throws Exception
     */
    public function execute($query, $params = null, $entity_name = null)
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
            if ($entity_name)
            {
                $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $entity_name);
            }
            else {
               $result->setFetchMode(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $result;
    }


    /**
     * @param string $table
     * @param array $fields
     * @param string|null $entity
     * @return array
     * @throws Exception
     */
    function select($table, $fields = array(), $entity = null)
    {
        if(count($fields)) {
            $query = 'SELECT * FROM ' . $table . ' WHERE ' . implode(' = ?, ', array_keys($fields)) . ' = ?';
            return $this->execute($query, array_values($fields), $entity)->fetchAll();
        }
        else {
            $query = 'SELECT * FROM ' . $table;
            return $this->execute($query, [], $entity)->fetchAll();
        }
    }

    /**
     * @param string $table
     * @param array $fields
     * @throws Exception
     */
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

    /**
     * @param string $table
     * @param array $fields
     * @throws Exception
     */
    public function update($table, $fields)
    {
        if(!is_array($fields) && !count($fields)) return;
        $query = 'UPDATE ' . $table . ' SET ' . implode(' = ?, ', array_keys($fields)) . ' = ? WHERE id = ?';
        $this->execute($query, array_values($fields));
    }

    /**
     * @return int
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}