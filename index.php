<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSessionPersistentDataHandler;

require 'vendor/autoload.php';

session_start();

$appId = '1148334785195709';
$appSecret = 'd8e2cb9e079641dcae34aca3e356bc4e';

FacebookSession::setDefaultApplication($appId, $appSecret);
$helper = new \Facebook\Helpers\FacebookRedirectLoginHelper('http://cuscom.fr/aaron/index.php');

//echo $helper->getLoginUrl();

