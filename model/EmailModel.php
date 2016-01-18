<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 14/01/2016
 * Time: 00:20
 */
class EmailModel
{
    private $conn;
    private $info;

    // email login credentials
    private $server = 'imap-mail.outlook.com';
    private $user = 'alex83690@live.fr';
    private $pass = 'SylverCrest';
    private $port = 993;

    public function getStreamById($id)
    {
        if (intval($id)) {
            $email = new EmailEntity();

            $db = new Database();
            $result = $db->execute("SELECT * FROM stream_email WHERE id = ?", array($id))->fetch();
            if ($result) {
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
    }

    public function getList()
    {
        $mails = imap_fetch_overview($this->conn, '1:' . $this->info->Nmsgs, 0);
        $articles = array();
        echo 'La boite aux lettres contient ' . $this->info->Nmsgs . ' message(s) dont ' .
            $this->info->Recent . ' recent(s)' .
                "<br />\n" .
                "<br />\n";
        foreach ($mails as $mail) {
            $article = new ArticleEntity();
            $article->setTitle($mail->subject.' - '.$mail->from);
            //$article->setContent('');
            $article->setDate($mail->date);
            $articles[] = $article;
        }
        return $articles;
    }

    function __construct()
    {
        $this->connect();
    }

    function close()
    {
        imap_close($this->conn);
    }

    function connect()
    {
        $conn = imap_open('{' . $this->server . ':' . $this->port . '/ssl}INBOX', $this->user, $this->pass);
        if (FALSE !== $conn) {
            $info = imap_check($conn);
            if (FALSE !== $info) {
                $this->conn = $conn;
                $this->info = $info;
            }
        }
    }
}