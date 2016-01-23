<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 22/01/16
 * Time: 11:33
 */
?>

<div id="feed">
    <?php
    if(!empty($articles))
    {
        /** @var ArticleEntity $article */
        foreach ($articles as $article)
        {
            ?>
            <div class="post">
                <?php

                switch($article->getStreamType()) {
                    case ArticleModel::EMAIL:
                        echo 'Twitter';
                        break;
                    case ArticleModel::RSS:
                        echo 'Twitter';
                        break;
                    case ArticleModel::TWITTER:
                        echo 'Twitter';
                        break;
                }
                ?>
                <?= $article->getTitle(); ?>
                <hr>
                <?= $article->getContent(); ?>
            </div>
            <?php
        }
    }
    ?>
</div>
