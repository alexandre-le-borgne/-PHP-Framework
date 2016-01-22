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
            $db = new Database();
            $data = $db->execute("SELECT * FROM stream_email WHERE id = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'EmailEntity');
            return $data->fetch();
        }
    }

    function decode_body($str)
    {
        return trim(utf8_encode(quoted_printable_decode($str)));
    }

    function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true)
    {

        foreach ($messageParts as $part) {
            $flattenedParts[$prefix . $index] = $part;
            if (isset($part->parts)) {
                if ($part->type == 2) {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix . $index . '.', 0, false);
                } elseif ($fullPrefix) {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix . $index . '.');
                } else {
                    $flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
                }
                unset($flattenedParts[$prefix . $index]->parts);
            }
            $index++;
        }

        return $flattenedParts;

    }

    function getPart($body, $encoding)
    {
        switch ($encoding) {
            case 0:
                return $body; // 7BIT
            case 1:
                return $body; // 8BIT
            case 2:
                return $body; // BINARY
            case 3:
                return base64_decode($body); // BASE64
            case 4:
                return quoted_printable_decode($body); // QUOTED_PRINTABLE
            case 5:
                return $body; // OTHER
        }
    }

    function getFilenameFromPart($part)
    {

        $filename = '';

        if ($part->ifdparameters) {
            foreach ($part->dparameters as $object) {
                if (strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if (!$filename && $part->ifparameters) {
            foreach ($part->parameters as $object) {
                if (strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }

    private function decode_imap_text($str)
    {
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

    public function decode7Bit($text)
    {
        // If there are no spaces on the first line, assume that the body is
        // actually base64-encoded, and decode it.
        $lines = explode("\r\n", $text);
        $first_line_words = explode(' ', $lines[0]);
        if ($first_line_words[0] == $lines[0]) {
            $text = base64_decode($text);
        }

        // Manually convert common encoded characters into their UTF-8 equivalents.
        $characters = array(
            '=20' => ' ', // space.
            '=E2=80=99' => "'", // single quote.
            '=0A' => "\r\n", // line break.
            '=A0' => ' ', // non-breaking space.
            '=C2=A0' => ' ', // non-breaking space.
            "=\r\n" => '', // joined line.
            '=E2=80=A6' => '…', // ellipsis.
            '=E2=80=A2' => '•', // bullet.
        );

        // Loop through the encoded characters and replace any that are found.
        foreach ($characters as $key => $value) {
            $text = str_replace($key, $value, $text);
        }

        return $text;
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
                        return mb_convert_encoding($text, "UTF-8", "auto");
                    case 1:
                        return utf8_encode($text);
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
        //$emails = imap_search($stream, 'SINCE '. date('d-M-Y',strtotime("-1 week")));
        $emails = imap_search($this->conn, 'ALL');
        $articles = array();
        if (count($emails)) {
            rsort($emails);
            foreach ($emails as $email) {

                // Fetch the email's overview and show subject, from and date.
                $overview = imap_fetch_overview($this->conn, $email, 0);
                echo $overview[0]->uid . ' : ';
                $structure = imap_fetchstructure($this->conn, $overview[0]->uid, FT_UID);
                if ($structure->encoding == "3") {
                    $body = base64_decode(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                } elseif ($structure->encoding == "0") {
                    $body = quoted_printable_decode(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                } elseif ($structure->encoding == "1") {
                    $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                } elseif ($structure->encoding == "4") {
                    $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                } else {
                    $body = imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1);
                }
                $article = new ArticleEntity();
                $article->setTitle("$$$$" . $structure->encoding . "$$$" . $this->decode_imap_text($overview[0]->subject) . ' - ' . $this->decode_imap_text($overview[0]->from));
                $article->setContent($this->getBody($overview[0]->uid, $this->conn));
                $article->setDate($overview[0]->date);
                $articles[] = $article;
            }
        }
        return $articles;
    }

    function __construct()
    {
    }

    function __destruct()
    {
    }

    private function connect($server, $port, $user, $password)
    {
        $conn = imap_open('{' . $server . ':' . $port . '/ssl}INBOX', $user, $password);
        if (FALSE !== $conn) {
            $info = imap_check($conn);
            if (FALSE !== $info) {
                return array('conn' => $conn, 'info' => $info);
            }
        }
        return false;
    }

    private function getFirstArticle(EmailEntity $emailEntity)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? AND articleType = ? ORDER BY articleDate ASC LIMIT 1',
            array($emailEntity->getId(), ArticleModel::EMAIL));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $result->fetch();
    }

    private function getLastArticle(EmailEntity $emailEntity)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? AND articleType = ? ORDER BY articleDate DESC LIMIT 1',
            array($emailEntity->getId(), ArticleModel::EMAIL));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $result->fetch();
    }

    public function cron()
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM stream_email');
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'EmailEntity');
        $emailStreams = $result->fetchAll();

        /** @var EmailEntity $emailEntity */
        foreach ($emailStreams as $emailEntity)
        {
            $firstEmail = $this->getFirstArticle($emailEntity);
            $lastEmail = $this->getFirstArticle($emailEntity);
            $connection = $this->connect($emailEntity->getServer(), $emailEntity->getPort(), $emailEntity->getUser(), $emailEntity->getPassword());
            $stream = $connection['conn'];
            $emails = imap_search($stream, 'SINCE ' . $emailEntity->getFirstUpdate());

            //$emails = imap_search($this->conn, 'ALL');

            $articles = array();
            if (count($emails))
            {
                foreach ($emails as $email)
                {
                    // Fetch the email's overview and show subject, from and date.
                    $overview = imap_fetch_overview($this->conn, $email, 0);
                    echo $overview[0]->uid . ' : ';
                    $structure = imap_fetchstructure($this->conn, $overview[0]->uid, FT_UID);
                    if ($structure->encoding == "3")
                    {
                        $body = base64_decode(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                    }
                    elseif ($structure->encoding == "0")
                    {
                        $body = quoted_printable_decode(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                    }
                    elseif ($structure->encoding == "1")
                    {
                        $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                    }
                    elseif ($structure->encoding == "4")
                    {
                        $body = imap_qprint(imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1));
                    }
                    else
                    {
                        $body = imap_fetchbody($this->conn, imap_msgno($this->conn, $overview[0]->uid), 1);
                    }
                    var_dump($overview[0]->date);
                    $article = new ArticleEntity();
                    $article->setTitle($this->decode_imap_text($overview[0]->subject) . ' - ' . $this->decode_imap_text($overview[0]->from));
                    $article->setContent($this->getBody($overview[0]->uid, $this->conn));
                    $article->setDate($overview[0]->date);
                    $articles[] = $article;
                }
            }
            /*
            $articlesARecuperer = array();
            /** @var ArticleEntity $article * /
            if ($firstEmail && $lastEmail)
            {
                foreach ($articles as $article)
                {
                    if ($article->getArticleDate() <= $firstEmail->getArticleDate)
                    {

                    }
                }
            }
            else {
                $articlesARecuperer = $articles;
            }*/
        }
        return $articles;
    }
}