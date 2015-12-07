<?php
error_reporting(E_ALL);

include_once("controllers/Controller.php");

$controller = new Controller();
$controller->invoke();