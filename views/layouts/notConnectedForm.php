<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 11:44
 */
$this->extend('persists/layout');
View::getView('forms/loginForm');
View::getView('forms/preRegisterForm', $this->output('errors'));
