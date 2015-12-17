<?php
//http://www.grafikart.fr/formations/programmation-objet-php/mvc-model-view-controller
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

require_once 'app/Request.php';
require_once 'app/Kernel.php';
require_once 'app/View.php';
require_once 'app/Controller.php';
require_once 'app/Database.php';
require_once 'app/Model.php';
require_once 'app/Route.php';
require_once 'app/Router.php';
require_once 'app/Securite.php';

require_once 'controller/IndexController.php';
require_once 'model/IndexModel.php';

Kernel::getInstance()->response();