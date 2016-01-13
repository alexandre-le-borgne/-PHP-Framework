<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 11:44
 */
$this->extend('persists/body');
echo "pd";
$this->insert('forms/loginForm');
echo "bite";
$this->insert('forms/preRegisterForm', $this->output('errors'));