<div class="post" id="post_<?= $article->getId() ?>">
    <?php
    switch ($article->getStreamType())
    {
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
            switch ($article->getStreamType())
            {
                case ArticleModel::EMAIL:
                    echo '<img src="' . View::getAsset('img/email.png') . '" width="60">';
                    break;
                case ArticleModel::RSS:
                    echo '<img src="' . View::getAsset('img/rss.png') . '" width="25">';
                    break;
                case ArticleModel::TWITTER:
                    echo '<img src="' . View::getAsset('img/twitter.png') . '" width="33">';
                    break;
                default:
                    echo '<img src="' . View::getAsset('img/default.png') . '" width="42">';
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
        if ($article->getStreamType() == ArticleModel::EMAIL)
        {
            echo '<iframe style="width: 100%; height: 500px;" src="' . View::getUrlFromRoute('email/' . $article->getId()) . '"></iframe>';
        }
        else
        {
            echo $article->getContent();
        }
        ?>
    </div>

    <div class="post_footer">
        <span class="follow" href="#">Suivre ce flux</span>
        <span class="ignore" href="#"><img src="<?= View::getAsset('img/hide.png') ?>" width="27"></span>
        <?php
        if ($favoris)
        {
            ?>
            <span class="like" href="#"><img src="<?= View::getAsset('img/like.png') ?>" width="27"></span>
            <?php
        }
        else
        {
            ?>
            <span class="nolike" href="#"><img src="<?= View::getAsset('img/nolike.png') ?>" width="27"></span>
            <?php
        }
        ?>
        <span class="repost" href="#"><img src="<?= View::getAsset('img/retweet.png') ?>" width="27"></span>
        <a class="global_url" target="_blank" href="<?= View::getUrlFromRoute('article/' . $article->getId()) ?>">
            <img src="<?= View::getAsset('img/share.png') ?>" width="27">
        </a>
    </div>
</div>
