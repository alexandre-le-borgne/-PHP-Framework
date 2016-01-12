<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 20:27
 */
?>
<!DOCTYPE html>
<html>
    <?php $view->render('exemple/head', array('title' => $title)); ?>
    <body>
        <h1><?= $view->output($title, 'Titre par défaut') ?></h1>
        <h2><?= $view->output($title2, 'Sous-Titre par défaut') ?></h2>

        <?= $_content ?>
    </body>
</html>
