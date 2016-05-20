<?php

class EntityManager implements Database
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

    public function persist(PersistableEntity $entity) {
        $fields = $entity->getFields();
        if(count($fields))
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
    
    public function flush() {
        foreach ($this->persistedEntitiesValues as $key => $persistedEntitiesValue) {
            /**
             * @var $entity PersistableEntity
             */
            $entity = $this->persistedEntities[$key];
            if($persistedEntitiesValue['insert']) {
                $this->insert($entity::getModel()->getTableName(), $persistedEntitiesValue['fields']);
                $entity->setCreatedAt(new DateTime());
                $entity->setId($this->lastInsertId());
            }
            else {
                $this->update($entity::getModel()->getTableName(), $persistedEntitiesValue['fields']);
            }
            $entity->setModifiedAt(new DateTime());
        }
    }

    function execute($query, $params = null, $entity = null)
    {
        return $this->database->execute($query, $params, $entity);
    }

    function insert($table, $fields)
    {
        $now = new DateTime();
        $now = $now->format($this->getDateFormat());
        $fields['created_at'] = $fields['modified_at'] = $now;
        $this->database->insert($table, $fields);
    }

    function update($table, $fields)
    {
        $now = new DateTime();
        $fields['modified_at'] = $now->format($this->getDateFormat());
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

    public function select($table, $fields = array(), $entity = null)
    {
        return $this->database->select($table, $fields, $entity);
    }
}