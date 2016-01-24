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
                        $type = 'email';
                        break;
                    case ArticleModel::RSS:
                        $type = 'rss';
                        break;
                    case ArticleModel::TWITTER:
                        $type = 'twitter';
                        break;
                }
                ?>
                <div class="post_header_<?= $type ?>">
                    <?= $article->getTitle(); ?>
                    <span class="author">
                        <?php

                        switch($article->getStreamType()) {
                            case ArticleModel::EMAIL:
                                echo '<img src="' . View::getAsset('img/email.png') . '" ' . 'width="60" ' . '"';
                                break;
                            case ArticleModel::RSS:
                                echo '<img src="' . View::getAsset('img/rss.png') . '" ' . 'width="25" ' . '"';
                                break;
                            case ArticleModel::TWITTER:
                                echo '<img src="' . View::getAsset('img/twitter.png') . '" ' . 'width="33" ' . '"';
                                break;
                            default:
                                echo '<img src="' . View::getAsset('img/default.png') . '" ' . 'width="42" ' . '"';
                        }
                        ?>
                    </span>
                </div>

                <div class="post_content">
                    <?php
                    /*
                    /if($article->getStreamType() == ArticleModel::TWITTER) {
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
                               * /
                        }
                    }
                     */
                    echo $article->getContent();
                    ?>
                </div>

                <div class="post_footer">
                    <a class="follow" href="#" >Suivre ce flux</a>
                    <a class="ignore" href="#" ><img src="<?= View::getAsset('img/show.png') ?>" width=""></a>
                    <a class="like" href="#" ><img src="<?= View::getAsset('img/like.png') ?>" width=""></a>
                    <a class="repost" href="#" ><img src="<?= View::getAsset('img/retweet.png') ?>" width=""></a>
                    <a class="global_url" href="#" ><img src="<?= View::getAsset('img/share.png') ?>" width=""></a>
                </div>

            </div>
            <?php
        }
    }
    ?>
</div>
