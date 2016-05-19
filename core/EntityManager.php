<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 13:50
 */
class EntityManager
{
    /**
     * @var $database Database
     */
    private $database;
    
    private $persistedEntitiesValues;
    private $persistedEntities;

    public function __construct(Database $database)
    {
        $this->database = $database;    
        $this->persistedEntities = array();
    }

    private function addPersistedEntity($entity, $values, $query, $insert)
    {
        $persistedEntityValues = array('entity' => $entity, 'values' => $values, 'query' => $query, 'insert' => $insert);
        $key = array_search($entity, $this->persistedEntities, true);
        if ($key === false)
        {
            $this->persistedEntities = $entity;
            $key = end($this->persistedEntities);
        }
        $this->persistedEntitiesValues[$key] = $persistedEntityValues;
    }
    
    public function persist(PersistableEntity $entity) {
        // TODO Sérialiser la class
        $fields = array('a' => 1, 'b' => 2, 'c' => 3);
        if(count($fields))
        {
            if ($entity->getId() == null)
            {
                $keys = array_keys($fields);
                $values = '';
                for ($i = 0; $i < count($keys); ++$i)
                    $values .= '?, ';
                $values = substr($values, 0, -2);
                $query = 'INSERT INTO ' . $entity->getTableName() . ' (' . implode(', ', $keys) . ') VALUES (' . $values . ')';
                $this->addPersistedEntity($entity, $values, $query, true);
            }
            else
            {
                $keys = array_keys($fields);
                $values = '';
                for ($i = 0; $i < count($keys); ++$i)
                    $values .= '?, ';
                $query = 'UPDATE ' . $entity->getTableName() . ' SET ' . implode(' = ?, ', $keys) . ' = ? WHERE id = ?';
                $this->addPersistedEntity($entity, array_values($fields), $query, false);
            }
        }
    }
    
    public function flush() {
        foreach ($this->persistedEntitiesValues as $persistedEntitiesValue) {
            $this->database->execute($persistedEntitiesValue['query'], $persistedEntitiesValue['values']);
            if($persistedEntitiesValue['insert'])
                $persistedEntitiesValue['entity']->setId($this->database->lastInsertId());
        }
    }
}