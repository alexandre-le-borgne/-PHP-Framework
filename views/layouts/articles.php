<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 23:38
 */
?>
<?php foreach ($articles as $article) : var_dump($article);?>
    <h1><?= $article.getTitle() ?></h1>
    <h2><?= $article.getDate() ?></h2>
    <p><?= $article.getContent() ?></p>
    <br><br><hr><br><br>
<?php endforeach; ?>

