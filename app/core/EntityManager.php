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
        $now = new DateTime();
        $now = $now->format($this->getDateFormat());
        foreach ($this->persistedEntitiesValues as $key => $persistedEntitiesValue) {
            $fields = $persistedEntitiesValue['fields'];
            $fields['modified_at'] = $now;

            /**
             * @var $entity PersistableEntity
             */
            $entity = $this->persistedEntities[$key];
            if($persistedEntitiesValue['insert']) {
                $fields['created_at'] = $now;
                $entity->setCreatedAt($now);

                $this->insert($entity::getModel()->getTableName(), $fields);
                $entity->setId($this->lastInsertId());
            }
            else {
                $this->update($entity::getModel()->getTableName(), $persistedEntitiesValue['fields']);
            }
            $entity->setModifiedAt($now);
        }
    }

    function execute($query, $params = null, $entity = null)
    {
        return $this->database->execute($query, $params, $entity);
    }

    function insert($table, $fields)
    {
        $this->database->insert($table, $fields);
    }

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

    public function select($table, $fields = array(), $entity = null)
    {
        return $this->database->select($table, $fields, $entity);
    }
}