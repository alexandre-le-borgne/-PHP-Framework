<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 14:25
 */
abstract class PersistableEntity extends Entity implements IPersistableEntity
{
    protected $createdAt = null;
    protected $modifiedAt = null;
    
    abstract function getFields();
    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }
}