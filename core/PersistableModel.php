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

    public function find($criteria) {
        if(is_integer($criteria)) {
            $this->getEntityManager()->select($this->getTableName(), array('id' => $criteria));
        }
        elseif(is_array($criteria)) {
            $this->getEntityManager()->select($this->getTableName(), $criteria);
        }
    }
}