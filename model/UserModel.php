<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 22:25
 */
class UserModel extends PersistableModel
{
    function getTableName()
    {
        return 'users';
    }
}