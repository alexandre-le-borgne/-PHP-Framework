<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 23:57
 */
abstract class PersistableModel extends Model
{
    abstract function getTableName();
    
    public function find($criteria = array()) {
        if(is_numeric($criteria)) {
            return $this->getEntityManager()->select($this->getTableName(), array('id' => $criteria), $this->getEntity());
        }
        else {
            return $this->getEntityManager()->select($this->getTableName(), $criteria, $this->getEntity());
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