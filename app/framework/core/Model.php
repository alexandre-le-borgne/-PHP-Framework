<?php

/**
 * Class Model
 */
abstract class Model
{
    /**
     * @var Entity
     */
    private $entity;

    /**
     * Model constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
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