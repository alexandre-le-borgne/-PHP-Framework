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
        if (is_numeric($id))
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
        if (is_numeric($id))
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
        if (is_numeric($id))
        {
            $db = new Database();
            $data = $db->execute("SELECT * FROM categories WHERE account = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CategoryEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function deleteCategory($id)
    {
        $db = new Database();
        $db->execute('DELETE FROM categories WHERE id = ?', array($id));
        $db->execute('DELETE FROM stream_category WHERE category = ?', array($id));
    }

    public function deleteAllCategoriesByAccount($userId)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM categories WHERE account = ?', array($userId));
        while ($fetch = $result->fetch())
        {
            $this->deleteCategory($fetch['id']);
        }
    }

    public function unsuscribeStream($userId, $streamId, $streamType)
    {
        $db = new Database();
        $req = 'DELETE FROM stream_category WHERE stream = ? AND streamType = ? AND category IN (SELECT id FROM categories WHERE account = ?)';
    }
}