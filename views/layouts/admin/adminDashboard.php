<?php
/**
 * Le pannel admin, qui mermet de supprimer, et voir tous les comptes utilisateur
 */

$view->extend('layouts/layout');
$this->render('persists/header');
?>

<a class="manageUsers" href="<?= View::getUrlFromRoute('adminusers') ?>">Gérer les utilisateurs</a>

























