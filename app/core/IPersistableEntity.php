<?php

/**
 * Created by PhpStorm.
 * User: GCC-MED
 * Date: 20/05/2016
 * Time: 16:08
 */
interface IPersistableEntity
{
    /**
     * @return PersistableModel
     */
    static function getModel();
}