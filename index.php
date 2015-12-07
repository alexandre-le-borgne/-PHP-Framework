<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
echo "error";

require 'vendor/autoload.php';

$appId = '1148334785195709';
$appSecret = 'd8e2cb9e079641dcae34aca3e356bc4e';

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();
