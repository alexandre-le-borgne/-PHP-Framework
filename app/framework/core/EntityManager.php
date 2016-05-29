<?php

/**
 * Class EntityManager
 */
final class EntityManager implements IDatabase
{
    /**
     * @var IDatabase
     */
    private $database;

    /**
     * @var array
     */
    private $persistedEntitiesValues;

    /**
     * @var array
     */
    private $persistedEntities;

    /**
     * EntityManager constructor.
     * @param IDatabase $database
     */
    public function __construct(IDatabase $database)
    {
        $this->database = $database;
        $this->persistedEntities = array();
    }

    /**
     * @param PersistableEntity $entity
     */
    public function persist(PersistableEntity $entity)
    {
        $fields = $entity->getPersistedFields();
        if (count($fields))
        {
            $persistedEntityValues = array('fields' => $fields, 'insert' => ($entity->getId() == null));
            $key = array_search($entity, $this->persistedEntities, true);
            if ($key === false)
            {
                $this->persistedEntities[] = $entity;
                $key = key($this->persistedEntities);
            }
            $this->persistedEntitiesValues[$key] = $persistedEntityValues;
        }
    }

    /**
     * Update all persisted entities in the database.
     */
    public function flush()
    {
        $now = new DateTime();
        $now = $now->format($this->getDateFormat());
        foreach ($this->persistedEntitiesValues as $key => $persistedEntitiesValue)
        {
            $fields = $persistedEntitiesValue['fields'];
            $fields['modified_at'] = $now;

            /**
             * @var $entity PersistableEntity
             */
            $entity = $this->persistedEntities[$key];
            if ($persistedEntitiesValue['insert'])
            {
                $fields['created_at'] = $now;
                $entity->setCreatedAt($now);

                $this->insert($entity::getModel()->getTableName(), $fields);
                $entity->setId($this->lastInsertId());
            }
            else
            {
                $this->update($entity::getModel()->getTableName(), $persistedEntitiesValue['fields']);
            }
            $entity->setModifiedAt($now);
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $entity
     * @return mixed
     */
    function execute($query, $params = null, $entity = null)
    {
        return $this->database->execute($query, $params, $entity);
    }

    /**
     * If third parameter $entity is precised, it return array of Entity
     * @param string $table
     * @param array $fields
     * @param string $entity
     * @return array
     */
    public function select($table, $fields = array(), $entity = null)
    {
        return $this->database->select($table, $fields, $entity);
    }

    /**
     * @param string $table
     * @param array $fields
     */
    function insert($table, $fields)
    {
        $this->database->insert($table, $fields);
    }

    /**
     * @param string $table
     * @param array $fields
     */
    function update($table, $fields)
    {
        $this->database->update($table, $fields);
    }

    /**
     * @return int
     */
    function lastInsertId()
    {
        return $this->database->lastInsertId();
    }

    /**
     * @return string
     */
    function getDateFormat()
    {
        return $this->database->getDateFormat();
    }
}