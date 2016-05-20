<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/05/2016
 * Time: 22:25
 */
class UserModel extends PersistableModel
{
// TODO eviter la duplication en la basculant dans une classe mere de persistableentity et de persistablemodel "linkToDataBase"
    function getTableName()
    {
        return 'users';
    }
}