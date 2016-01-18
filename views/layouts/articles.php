<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 23:38
 */
?>
<?php foreach ($articles as $article) : ?>
    <h4><?= $article->getTitle() ?></h4>
    <h5><?= $article->getDate() ?></h5>
    <p><?= $article->getContent() ?></p>
    <br><br><hr><br><br>
<?php endforeach; ?>