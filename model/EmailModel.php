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

    function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

        foreach($messageParts as $part) {
            $flattenedParts[$prefix.$index] = $part;
            if(isset($part->parts)) {
                if($part->type == 2) {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
                }
                elseif($fullPrefix) {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
                }
                else {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
                }
                unset($flattenedParts[$prefix.$index]->parts);
            }
            $index++;
        }

        return $flattenedParts;

    }

    function getPart($body, $encoding) {
        switch($encoding) {
            case 0: return $body; // 7BIT
            case 1: return $body; // 8BIT
            case 2: return $body; // BINARY
            case 3: return base64_decode($body); // BASE64
            case 4: return quoted_printable_decode($body); // QUOTED_PRINTABLE
            case 5: return $body; // OTHER
        }
    }

    function getFilenameFromPart($part) {

        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }

    private function decode_imap_text($str){
        $result = '';
        $decode_header = imap_mime_header_decode($str);
        foreach ($decode_header AS $obj) {
            $result .= htmlspecialchars(rtrim($obj->text, "\t"));
        }
        return $result;
    }

    function getBody($uid, $imap)
    {
        $body = $this->get_part($imap, $uid, "TEXT/HTML");
        // if HTML body is empty, try getting text body
        if ($body == "") {
            $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }

    function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false)
    {
        if (!$structure) {
            $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
                switch ($structure->encoding) {
                    case 3:
                        return imap_base64($text);
                    case 4:
                        return imap_qprint($text);
                    case 0:
                        return imap_utf8($text);
                    case 1:
                        return imap_utf8($text);
                    default:
                        return $text;
                }
            }

            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    function get_mime_type($structure)
    {
        $primaryMimetype = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];

        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    public function getList()
    {
        echo 'V9';
        //$emails = imap_search($stream, 'SINCE '. date('d-M-Y',strtotime("-1 week")));
        $emails = imap_search($this->conn, 'ALL');
        $articles = array();
        if (count($emails)){
            // If we've got some email IDs, sort them from new to old and show them
            rsort($emails);
            foreach($emails as $email) {

                // Fetch the email's overview and show subject, from and date.
                $overview = imap_fetch_overview($this->conn,$email,0);
                echo $overview[0]->uid. ' : ';
                $structure = imap_fetchstructure($this->conn,$overview[0]->uid, FT_UID);
                if($structure->encoding == "3"){
                    $body = base64_decode(imap_fetchbody($this->conn, imap_msgno($this->conn,$overview[0]->uid), 1));
                }
                elseif($structure->encoding == "0") {
                    $body = quoted_printable_decode(imap_fetchbody($this->conn, imap_msgno($this->conn,$overview[0]->uid), 1));
                }
                elseif($structure->encoding == "1") {
                    $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn,$overview[0]->uid), 1));
                }
                elseif($structure->encoding == "4"){
                    $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn,$overview[0]->uid), 1));
                }else{
                    $body = imap_fetchbody($this->conn, imap_msgno($this->conn,$overview[0]->uid), 1);
                }
                $article = new ArticleEntity();
                $article->setTitle("$$$$".$structure->encoding . "$$$" .$this->decode_imap_text($overview[0]->subject) . ' - ' . $this->decode_imap_text($overview[0]->from));
                $article->setContent($this->getBody($overview[0]->uid, $this->conn));
                $article->setDate($overview[0]->date);
                $articles[] = $article;
            }
        }

        /*
        $mails = imap_fetch_overview($this->conn, '1:' . $this->info->Nmsgs, 0);
        $articles = array();
        echo 'La boite aux lettres contient ' . $this->info->Nmsgs . ' message(s) dont ' .
            $this->info->Recent . ' recent(s)' .
            "<br />\n" .
            "<br />\n";
        foreach ($mails as $mail) {
            $headerText = imap_fetchHeader($this->conn, $mail->uid, FT_UID & FT_PEEK);
            $header = imap_rfc822_parse_headers($headerText);
            //$corps = imap_fetchbody($this->conn, $mail->uid, 1, FT_UID & FT_PEEK);
            /*$corps = imap_fetchbody($this->conn, $mail->uid,1.2);
            if(!strlen($corps)>0){
                $corps = imap_fetchbody($this->conn, $mail->uid,1);
            }* /
            $corps = imap_qprint(imap_fetchbody($this->conn, $mail->uid, 1.2));
            $article = new ArticleEntity();
            $article->setTitle($this->decode_body($mail->subject) . ' - ' . imap_utf8($header->from[0]->personal . ' [' . $header->from[0]->mailbox . '@' . $header->from[0]->host . ']'));
            $article->setContent($corps);
            $article->setDate(imap_utf8($mail->date));
            $articles[] = $article;
        }
        */
        return $articles;
    }

    function __construct()
    {
        $this->connect();
    }

    function __destruct()
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