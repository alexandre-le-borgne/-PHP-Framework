<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */

class UserEntity extends PersistableEntity
{
    function getTableName()
    {
        return 'users';
    }
}