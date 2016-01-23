<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/01/2016
 * Time: 16:30
 */
class CategoryModel extends Model
{
    public function getById($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $data = $db->execute("SELECT * FROM categories WHERE id = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CategoryEntity');
            return $data->fetch();
        }
        return null;
    }

    public function getByUserId($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $data = $db->execute("SELECT * FROM categories WHERE account = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CategoryEntity');
            return $data->fetchAll();
        }
        return null;
    }
}