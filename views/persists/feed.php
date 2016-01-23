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
                <div class="post_header">
                    <?= $article->getTitle(); ?>
                    <span class="author">
                        <?php

                        switch($article->getStreamType()) {
                            case ArticleModel::EMAIL:
                                echo 'Email';
                                break;
                            case ArticleModel::RSS:
                                echo 'RSS';
                                break;
                            case ArticleModel::TWITTER:
                                echo 'Twitter';
                                break;
                        }
                        ?>
                    </span>
                </div>

                <div class="post_content">
                    CONTENT :
                    <?php
                    if($article->getStreamType() == ArticleModel::TWITTER) {
                        echo "2 - " . htmlentities($article->getContent());
                        $doc = new DOMDocument();
                        $doc->loadHTML($article->getContent());

                        $tags = $doc->getElementsByTagName('a');

                        foreach ($tags as $tag) {
                            echo $tag->getAttribute('href');/*
                            ?>
                            <a href="<?= $tag->getAttribute('href') ?>" target="_blank">
                                <img src="http://www.robothumb.com/src/?url=<?= $tag->getAttribute('href') ?>&size=240x180">
                            </a>
                            <?php
                               */
                        }
                    }

                    else {
                        echo $article->getContent();
                    }
                    ?>
                </div>

                <div id="post_footer">
                    FOOTER
                </div>

            </div>
            <?php
        }
    }
    ?>
</div>
