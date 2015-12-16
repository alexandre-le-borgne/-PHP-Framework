<?php
//http://www.grafikart.fr/formations/programmation-objet-php/mvc-model-view-controller
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

require 'class/Router.php';

$router = new Router($_GET['url']);
$router->addGet('/', function(){ echo "Bienvenue sur ma homepage !"; });
$router->addGet('/posts/:id', function($id){ echo "Voila l'article $id"; });
$router->run();

//echo $helper->getLoginUrl();

