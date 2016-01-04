<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

/**
 * Les fonctions principales pour le fonctionnement du MVC
 */
require_once 'app/TraceableException.php';
require_once 'app/Session.php';
require_once 'app/Request.php';
require_once 'app/Kernel.php';
require_once 'app/View.php';
require_once 'app/Controller.php';
require_once 'app/Database.php';
require_once 'app/Model.php';
require_once 'app/Route.php';
require_once 'app/Router.php';
require_once 'app/Mail.php';
require_once 'app/Security.php';

/**
 * Les Differents controlleurs
 */
require_once 'controller/IndexController.php';
require_once 'controller/UserController.php';

/**
 * Les Differents modeles
 */
require_once 'model/IndexModel.php';
require_once 'model/PostModel.php';
require_once 'model/FeedRSSModel.php';

try {
    Kernel::getInstance()->response();
}
catch(TraceableException $e) {
    echo $e->show();
}
