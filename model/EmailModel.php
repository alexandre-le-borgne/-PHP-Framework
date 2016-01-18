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

    function decode_body($str)
    {
        return trim( utf8_encode( quoted_printable_decode( $str)));
    }


    function retrieve_message($mbox, $messageid)
    {
        $message = array();

        $header = imap_header($mbox, $messageid);
        $structure = imap_fetchstructure($mbox, $messageid, FT_UID);

        $message['subject'] = $header->subject;
        $message['fromaddress'] =   $header->fromaddress;
        $message['toaddress'] =   $header->toaddress;
        $message['ccaddress'] =   $header->ccaddress;
        $message['date'] =   $header->date;

        if ($this->check_type($structure))
        {
            $message['body'] = imap_fetchbody($mbox,$messageid,"1"); ## GET THE BODY OF MULTI-PART MESSAGE
            if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
        }
        else
        {
            $message['body'] = imap_body($mbox, $messageid);
            if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
        }

        return $message;
    }

    function check_type($structure) ## CHECK THE TYPE
    {
        if($structure->type == 1)
        {
            return(true); ## YES THIS IS A MULTI-PART MESSAGE
        }
        else
        {
            return(false); ## NO THIS IS NOT A MULTI-PART MESSAGE
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
            $headerText = imap_fetchHeader($this->conn, $mail->uid, FT_UID & FT_PEEK);
            $header = imap_rfc822_parse_headers($headerText);
            $corps = imap_fetchbody($this->conn, $mail->uid, 1, FT_UID & FT_PEEK);
            $message = $this->retrieve_message($this->conn, $mail->uid);
            $article = new ArticleEntity();
            $article->setTitle($message['subject'] . ' - [' . $message['fromaddress'] . ']');
            $article->setContent($message['body']);
            $article->setDate($message['date']);
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