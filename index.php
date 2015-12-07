<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
echo "error";

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();
