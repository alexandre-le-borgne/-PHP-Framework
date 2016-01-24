<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 24/01/2016
 * Time: 16:53
 */
?>

<div id="streams">
    <h2>Mes flux</h2>
    <!--<div class="item_streams"><img id="add_stream" src="<?php //View::getAsset('img/add_cat.png') ?>"></div>-->
    <?php
    if (!empty($emailStreams))
    {
        /** @var EmailEntity $emailStream */
        foreach ($emailStreams as $emailStream)
        {
            ?>
            <a class="item_streams email" href="<?= View::getUrlFromRoute('stream/' . ArticleModel::EMAIL. '/' . $emailStream->getId()) ?>">
                <?= $emailStream->getAccount() ?>
            </a>
            <?php
        }
    }
    if (!empty($twitterStreams))
    {
        /** @var TwitterEntity $twitterStream */
        foreach ($twitterStreams as $twitterStream)
        {
            ?>
            <a class="item_streams twitter" href="<?= View::getUrlFromRoute('stream/' . ArticleModel::TWITTER.'/' . $twitterStream->getId()) ?>">
                <?= $twitterStream->getChannel() ?>
            </a>
            <?php
        }
    }
    if (!empty($rssStreams))
    {
        /** @var RssEntity $rssStreams */
        foreach ($rssStreams as $rssStream)
        {
            ?>
            <a class="item_streams rss" href="<?= View::getUrlFromRoute('stream/' . ArticleModel::RSS . '/' . $rssStream->getId()) ?>">
                <?= $rssStream->getUrl() ?>
            </a>
            <?php
        }
    }
    ?>
</div>
