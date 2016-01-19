<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 17:04
 */

echo "Couilles !!!";
$view->extend('layouts/layout');
$this->render('persist/header');
echo $this->output('_content');

