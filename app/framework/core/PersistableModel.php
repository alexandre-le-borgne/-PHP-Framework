<?php

/**
 * Class PersistableModel
 */
abstract class PersistableModel extends Model
{
    /**
     * @return string
     */
    abstract function getTableName();

    /**
     * @param array $criteria
     * @return array
     */
    public function find($criteria = array())
    {
        if (is_numeric($criteria))
        {
            return $this->getEntityManager()->select($this->getTableName(), array('id' => $criteria), $this->getEntityName());
        }
        else
        {
            return $this->getEntityManager()->select($this->getTableName(), $criteria, $this->getEntityName());
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return Kernel::getInstance()->getEntityManager();
    }
}