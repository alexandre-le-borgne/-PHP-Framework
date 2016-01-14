<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 14/01/2016
 * Time: 00:20
 */
class EmailModel
{
    public $conn;

    // inbox storage and inbox message count
    private $inbox;
    private $msg_cnt;

    // email login credentials
    private $server = 'imap-mail.outlook.com';
    private $user   = 'alex83690@live.fr';
    private $pass   = 'SylverCrest';
    private $port   = 993;

    public function getById($id) {
        if (intval($id))
        {
            $email = new EmailEntity();

            $db = new Database();
            $result = $db->execute("SELECT * FROM stream_email WHERE id = ?", array($id))->fetch();
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
    }

    public function load() {
        $db = new Database();
        $req = $db->execute("SELECT * FROM stream_email");
        $result = $req->fetch();
        $emails = array();
        while($result) {
            $emails[] = new EmailEntity($result['id']);
        }
    }

    public function availableUser($username)
    {
        $db = new Database();
        $sql = "SELECT * FROM accounts WHERE username = ?";
        return $db->execute($sql, array($username));
    }

    public function availableEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $this::BAD_EMAIL_REGEX;
        $db = new Database();
        $sql = "SELECT * FROM accounts WHERE email = ?";
        $result = $db->execute($sql, array($email));
        if (!($result->fetch())) //Resultat requete vide
            return $this::CORRECT_EMAIL;
        return $this::ALREADY_USED_EMAIL;
    }

    public function correctPwd($password)
    {
        return (6 <= strlen($password) && strlen($password) <= 20);
    }


    public function addUser($username, $email, $password, $birthDate)
    {
        $db = new Database();
        $key = Security::generateKey();
        $password = Security::encode($password);

        $db->execute("INSERT INTO accounts (username, email, authentification, birthDate, userKey) VALUES (?, ?, "
            . UserModel::AUTHENTIFICATION_BY_PASSWORD . ", ?, ?)", array($username, $email, $birthDate, $key));

        $id = $db->lastInsertId();

        $db->execute("INSERT INTO passwords (user, password) VALUES (?, ?)", array($id, $password));

        Mail::sendVerificationMail($username, $email, $key);
    }


    // imap server connection
    // adjust according to server settings

    // connect to the server and get the inbox emails
    function __construct() {
        $this->connect();
        $this->inbox();
    }

    // close the server connection
    function close() {
        $this->inbox = array();
        $this->msg_cnt = 0;

        imap_close($this->conn);
    }

    // open the server connection
    // the imap_open function parameters will need to be changed for the particular server
    // these are laid out to connect to a Dreamhost IMAP server
    function connect() {
        $this->conn = imap_open('{'.$this->server.':'.$this->port.'/ssl}INBOX', $this->user, $this->pass);
    }

    // move the message to a new folder
    function move($msg_index, $folder='INBOX.Processed') {
        // move on server
        imap_mail_move($this->conn, $msg_index, $folder);
        imap_expunge($this->conn);

        // re-read the inbox
        $this->inbox();
    }

    // get a specific message (1 = first email, 2 = second email, etc.)
    function get($msg_index=NULL) {
        if (count($this->inbox) <= 0) {
            return array();
        }
        elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }

        return $this->inbox[0];
    }

    function getpart($mbox,$mid,$p,$partno) {
        // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
        global $htmlmsg,$plainmsg,$charset,$attachments;

        // DECODE DATA
        $data = ($partno)?
            imap_fetchbody($mbox,$mid,$partno):  // multipart
            imap_body($mbox,$mid);  // simple
        // Any part may be encoded, even plain text messages, so check everything.
        if ($p->encoding==4)
            $data = quoted_printable_decode($data);
        elseif ($p->encoding==3)
            $data = base64_decode($data);

        // PARAMETERS
        // get all parameters, like charset, filenames of attachments, etc.
        $params = array();
        if (isset($p->parameters) && is_array($p->parameters) && $p->parameters)
            foreach ($p->parameters as $x)
                $params[strtolower($x->attribute)] = $x->value;
        if (isset($p->dparameters) && is_array($p->dparameters) && $p->dparameters)
            foreach ($p->dparameters as $x)
                $params[strtolower($x->attribute)] = $x->value;

        // ATTACHMENT
        // Any part with a filename is an attachment,
        // so an attached text file (type 0) is not mistaken as the message.
        if ((isset($params['filename']) && $params['filename']) || (isset($params['name']) && $params['name'])) {
            // filename may be given as 'Filename' or 'Name' or both
            $filename = (isset($params['filename']) && $params['filename']) ? $params['filename'] : $params['name'];
            // filename may be encoded, so see imap_mime_header_decode()
            $attachments[$filename] = $data;  // this is a problem if two files have same name
        }

        // TEXT
        if ($p->type==0 && $data) {
                // Messages may be split in different parts because of inline attachments,
                // so append parts together with blank row.
                if (strtolower($p->subtype)=='plain')
                    $plainmsg .= trim($data) ."\n\n";
            else
                $htmlmsg .= $data ."<br><br>";
            $charset = (isset($params['charset']) ? $params['charset'] : '');  // assume all parts are same charset
        }

        // EMBEDDED MESSAGE
        // Many bounce notifications embed the original message as type 2,
        // but AOL uses type 1 (multipart), which is not handled here.
        // There are no PHP functions to parse embedded messages,
        // so this just appends the raw source to the main message.
        elseif ($p->type==2 && $data) {
            $plainmsg .= $data."\n\n";
        }

        // SUBPART RECURSION
        if (isset($p->parts) && $p->parts) {
            foreach ($p->parts as $partno0=>$p2)
                $this->getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
        }
    }

    // read the inbox
    function inbox() {
        global $charset,$htmlmsg,$plainmsg,$attachments;
        $this->msg_cnt = imap_num_msg($this->conn);

        $in = array();
        for($i = 1; $i <= $this->msg_cnt; $i++) {
            $s = imap_fetchstructure($this->conn, $i);
            if (!isset($s->parts) || !$s->parts)  // simple
                $this->getpart($this->conn, $i, $s,0);  // pass 0 as part-number
            else {  // multipart: cycle through each part
                foreach ($s->parts as $partno0=>$p)
                    $this->getpart($this->conn, $i, $p, $partno0+1);
            }
            $in[] = array(
                'index'     => $i,
                'header'    => imap_headerinfo($this->conn, $i),
                'body'      => $htmlmsg,
                'structure' => imap_fetchstructure($this->conn, $i)
            );
        }

        $this->inbox = $in;
    }
}