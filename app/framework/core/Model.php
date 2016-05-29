<?php

/**
 * Class Model
 */
abstract class Model
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * Model constructor.
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}