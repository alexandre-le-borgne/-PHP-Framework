<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/01/2016
 * Time: 16:30
 */
class CategoryModel extends Model
{
    public function createCategory($account, $title)
    {
        $db = new Database();
        $data = array($account, $title);
        $data = $db->execute("SELECT * FROM categories WHERE account = ? AND title = ?", $data);
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CategoryEntity');
        $categoryEntity = $data->fetch();
        if ($categoryEntity)
        {
            return $categoryEntity;
        }
        else
        {
            $categoryEntity = new CategoryEntity();
            $categoryEntity->setAccount($account);
            $categoryEntity->setTitle($title);
            $categoryEntity->persist();
            return $categoryEntity;
        }
    }

    public function getStreamCategoriesByCategoryId($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $req = 'SELECT * FROM stream_category WHERE category = ?';
            $result = $db->execute($req, array($id));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'StreamCategoryEntity');
            return $result->fetchAll();
        }
        return null;
    }

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

    /**
     * Renvoit un tableau de streamCategory entity avec le titre de la category, et l'url du stream associé, qui est
     * plus exploitable que des ID

    public function getStreamsByCategoryId($id)
    {
        if (intval($id))
        {
            $return = array();
            $db = new Database();
            $req =
                'SELECT c1.id,  FROM categories c1 JOIN stream_categories sc1 ON c1.id = sc1.category JOIN stream_email s1'
            $result = $db->execute("SELECT * FROM categories JOIN stream_category ON categories.id = stream_category.category WHERE categories.id = ?", array($id));
            while ($fetch = $result->fetch())
            {
                $streamCategory = new StreamCategoryEntity();
                $streamCategory->setId($fetch['stream_category.id']);
                $streamCategory->setStream($fetch['']);//l'url du stream
                $streamCategory->setCategory($fetch['categories.title']);
                $streamCategory->setStreamType($fetch['streamType']);
            }
            return $data->fetch();
        }
    }*/
}