<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/01/2016
 * Time: 14:07
 */
$view->extend('layouts/layoutConnected');
if($this->isGranted(Session::USER_IS_CONNECTED)) {
    $this->render('forms/logoutForm');
}
else {
    ?>
    <a href="login">Se connecter</a>
    <?php
}
echo $this->output('home');
