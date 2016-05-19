<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 19/05/2016
 * Time: 14:25
 */
abstract class PersistableEntity extends Entity
{
    abstract function getTableName();  
}