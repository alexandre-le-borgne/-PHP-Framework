<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 17:05
 */

$view->extend('layouts/layout');
$this->render('persists/header');
?>

<a href="<? View::getUrlFromRoute('streamadd') ?>">Ajouter un flux</a>

<?php
echo $this->output('_content');

