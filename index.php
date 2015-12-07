<?php
error_reporting(E_ALL);
echo BITASSE;

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();