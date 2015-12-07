<?php
error_reporting(E_ALL);
echo $_SESSION;

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();