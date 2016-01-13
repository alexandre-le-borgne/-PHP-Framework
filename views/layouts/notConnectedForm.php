<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 11:44
 */
$this->extend('persists/body');
echo "pd";
$this->render('forms/loginForm');
echo "bite";
$this->render('forms/preRegisterForm', $this->output('errors'));