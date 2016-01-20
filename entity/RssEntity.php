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

    function __construct($id)
    {
        $this->id = $id;

        if(intval($id)){
            $db = new Database();
            $req = "Select * From stream_rss Where Id = ?";
            $result = $db->excecute($req, array($id))->fetch();

            if($result) {
                $this->id = $result['id'];
                $this->url = $result['url'];
                $this->lastUpdate = $result['lastUpdate'];
                $this->firstUpdate = $result['firstUpdate'];
                return;
            }
        }
        throw new TraceableException("Flux rss manquant");
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @return mixed
     */
    public function getFirstUpdate()
    {
        return $this->firstUpdate;
    }




}