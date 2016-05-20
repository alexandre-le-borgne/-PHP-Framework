<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 14:25
 */
abstract class PersistableEntity extends Entity implements IPersistableEntity
{
    protected $created_at = null;
    protected $modified_at = null;
    
    abstract function getFields();
    
    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param string $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
    }
}