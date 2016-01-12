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
        <h1><?= $title ?></h1>
        <?= $_content ?>
    </body>
</html>
