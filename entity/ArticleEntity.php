<?php
/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/01/16
 * Time: 10:31
 */

class ArticleEntity {
    private $id, $title, $content, $date;

    public function __construct($id) {
        if (intval($id))
        {
            $db = new Database();
            $result = $db->execute("SELECT * FROM article WHERE id = ?", array($id))->fetch();
            if($result) {
                $this->id = $result['id'];
                $this->server = $result['server'];
                $this->user = $result['user'];
                $this->password = $result['password'];
                $this->port = $result['port'];
                $this->firstUpdate = $result['firstUpdate'];
                $this->lastUpdate = $result['lastUpdate'];
                return;
            }
        }
        throw new TraceableException("Article non crée !");
    }
}