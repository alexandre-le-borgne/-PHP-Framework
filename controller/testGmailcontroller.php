<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 13/01/16
 * Time: 15:17
 */

function gmail_login_page()
{
    ?>
    <html>
    <head><title>Gmail summary login</title>
        <style type="text/css">body { font-family: arial, sans-serif; margin: 40px;}</style>
    </head>
    <body>
    <div>This page demonstrates how to access your Gmail account using IMAP in PHP. </div><br/>
    <div>Enter your full email address and password, and the next page will show a selection of information about your account.</div><br/>
    <div>See <a href="http://petewarden.typepad.com/">http://petewarden.typepad.com/</a&gt; for more information.</div><br/>
    <hr/><br/>
    <div>
        <form action="index.php" method="POST">
            <input type="text" name="user"> Gmail address<br/>
            <input type="password" name="password"> Password<br/>
            <br/>
            <input type="submit" value="Get summary">
        </form>
    </div>
    <hr/>
    </body>
    </html>
    <?php
}

function gmail_summary_page($user, $password)
{
    ?>
    <html>
    <head><title>Gmail summary for <?=$user?></title>
        <style type="text/css">body { font-family: arial, sans-serif; margin: 40px;}</style>
    </head>
    <body>
    <?php

    $imapaddress = "{imap.gmail.com:993/imap/ssl}";
    $imapmainbox = "INBOX";
    $maxmessagecount = 10;

    display_mail_summary($imapaddress, $imapmainbox, $user, $password, $maxmessagecount);
    ?>
    </body>
    </html>
    <?php
}

function display_mail_summary($imapaddress, $imapmainbox, $imapuser, $imappassword, $maxmessagecount)
{
    $imapaddressandbox = $imapaddress . $imapmainbox;

    $connection = imap_open ($imapaddressandbox, $imapuser, $imappassword)
    or die("Can’t connect to ‘" . $imapaddress .
        "’ as user ‘" . $imapuser .
        "’ with password ‘" . $imappassword .
        "’: " . imap_last_error());

    echo "<u><h1>Gmail information for " . $imapuser ."</h1></u>";

    echo "<h2>Mailboxes</h2>\n";
    $folders = imap_listmailbox($connection, $imapaddress, "*")
    or die("Can’t list mailboxes: " . imap_last_error());

    foreach ($folders as $val)
        echo $val . "<br />\n";

    echo "<h2>Inbox headers</h2>\n";
    $headers = imap_headers($connection)
    or die("can’t get headers: " . imap_last_error());

    $totalmessagecount = sizeof($headers);

    echo $totalmessagecount . " messages<br/><br/>";

    if ($totalmessagecount<$maxmessagecount)
        $displaycount = $totalmessagecount;
    else
        $displaycount = $maxmessagecount;

    for ($count=1; $count<=$displaycount; $count+=1)
    {
        $headerinfo = imap_headerinfo($connection, $count)
        or die("Couldn’t get header for message " . $count . " : " . imap_last_error());
        $from = $headerinfo->fromaddress;
        $subject = $headerinfo->subject;
        $date = $headerinfo->date;
        echo "<em><u>".$from."</em></u>: ".$subject." – <i>".$date."</i><br />\n";
    }

    echo "<h2>Message bodies</h2>\n";

    for ($count=1; $count<=$displaycount; $count+=1)
    {
        $body = imap_body($connection, $count)
        or die("Can’t fetch body for message " . $count . " : " . imap_last_error());
        echo "<pre>". htmlspecialchars($body) . "</pre><hr/>";
    }

    imap_close($connection);
}

$user = $_POST["user"];
$password = $_POST["password"];

if (!$user or !$password)
    gmail_login_page();
else
    gmail_summary_page($user, $password);

?>

<h1><strong>BOUFFE MES COUILLES</strong></h1>
