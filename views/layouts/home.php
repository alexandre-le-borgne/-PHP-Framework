<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/01/2016
 * Time: 14:07
 */
$view->extend('layouts/layoutConnected');
$this->render('forms/logoutForm');
echo $this->output('home');
