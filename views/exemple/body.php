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
    <h1><?= $this->output('title', 'Titre par défaut') ?></h1>
    <h2><?= $this->output('title2', 'Sous-Titre par défaut') ?></h2>
    <?php if($this->isGranted(Session::USER_IS_CONNECTED)): ?>
        L'utilisateur est connecté !
    <?php endif; ?>
    <?= $_content ?>
    <hr>
    <?= $this->render('exemple/content'); ?>
    <hr>
</body>
