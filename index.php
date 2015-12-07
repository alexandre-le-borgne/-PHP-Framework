<?php
error_reporting(E_ALL);
echo 'echocho';

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();