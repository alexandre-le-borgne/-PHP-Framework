<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 14:47
 */
$view->extend('exemple/layout');
?>
<body>
    <h1><?= output('title', 'Titre par défaut') ?></h1>
    <h2><?= output('title2', 'Sous-Titre par défaut') ?></h2>
    <?php if(isGranted(Session::USER_IS_CONNECTED)): ?>
        L'utilisateur est connecté !
    <?php endif; ?>
    <?= $_content ?>
    <hr>
    <?= render('exemple/content'); ?>
    <hr>
</body>
