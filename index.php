<?php
//http://www.grafikart.fr/formations/programmation-objet-php/mvc-model-view-controller
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

$router = new Router($_GET['url']);
$router->addGet('/', function(){ echo "Bienvenue sur ma homepage !"; });
$router->addGet('/posts/:id', function($id){ echo "Voila l'article $id"; });
$router->run();

//echo $helper->getLoginUrl();

