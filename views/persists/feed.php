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
                <div id="post_author">
                    AUTHOR :
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
                </div>

                <div id="post_header">
                    HEADER :
                    <?= $article->getTitle(); ?>
                </div>

                <div id="post_content">
                    CONTENT :
                    <?php
                    if($article->getStreamType() == ArticleModel::TWITTER) {
                        echo htmlentities($article->getContent());
                        preg_match('/a>/i', $article->getContent(), $result);
                        print_r($result);
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
