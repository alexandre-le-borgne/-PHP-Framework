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
                $email->setId($result['id']);
                $email->setServer($result['server']);
                $email->setUser($result['user']);
                $email->setPassword($result['password']);
                $email->setPort($result['port']);
                $email->setFirstUpdate($result['firstUpdate']);
                $email->setLastUpdate($result['lastUpdate']);
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
            $headerText = imap_fetchHeader($this->conn, $mail->uid, FT_UID);
            $header = imap_rfc822_parse_headers($headerText);
            $corps = imap_fetchbody($this->conn, $mail->uid, 1, FT_UID);
            $article = new ArticleEntity();
            $article->setTitle(quoted_printable_decode ($mail->subject).' - '.imap_utf8($header->from[0]->personal.' ['.$header->from[0]->mailbox.'@'.$header->from[0]->host.']'));
            $article->setContent(quoted_printable_decode ($corps));
            $article->setDate(imap_utf8($mail->date));
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