<?php

abstract class Model
{
    private $entity;
    
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}