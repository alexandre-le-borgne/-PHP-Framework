<?php

/**
 * Class Model
 */
abstract class Model
{
    /**
     * @var string
     */
    private $entity;

    /**
     * Model constructor.
     * @param string $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
}