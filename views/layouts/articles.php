<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 23:38
 */

$view->extend('layouts/layoutConnected');
foreach ($articles as $article) : ?>
    <b><?= $article->getTitle() ?></b><br>
    <i><?= $article->getDate() ?></i><br>
    <p><?= $article->getContent() ?></p>
    <br><br><hr><br><br>
<?php endforeach; ?>