<?php
/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 14/01/16
 * Time: 09:30
 */

class RssEntity extends Entity{

    private $id;
    private $url;
    private $lastUpdate;
    private $firstUpdate;

    public function toString()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param mixed $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return mixed
     */
    public function getFirstUpdate()
    {
        return $this->firstUpdate;
    }

    /**
     * @param mixed $firstUpdate
     */
    public function setFirstUpdate($firstUpdate)
    {
        $this->firstUpdate = $firstUpdate;
    }


}