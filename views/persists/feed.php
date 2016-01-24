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
    var_dump($articles);
    if(!empty($articles))
    {
        /** @var ArticleEntity $article */
        foreach ($articles as $article)
        {
            echo $article->getId();
            echo $this->renderControllerAction('article', array($article->getId()));
        }
        ?>
        <script>
            $(document).on("click", ".post_footer .like", function() {
                var thispost = this;
                var post = $(this).parents(".post");
                var data = {
                    action: 'nolike',
                    id: post.attr("id"),
                };
                $.ajax({
                    method: "POST",
                    url: "<?= View::getUrlFromRoute('ajax') ?>",
                    data: data
                }).done(function( msg ) {
                    $("img", thispost).attr('src', '<?= View::getAsset('img/nolike.png') ?>');
                    $(thispost).removeClass('like').addClass('nolike');
                });
            });
            $(document).on("click", ".post_footer .nolike", function() {
                console.log("t");
                var thispost = this;
                var post = $(this).parents(".post");
                var data = {
                    action: 'like',
                    id: post.attr("id"),
                };
                $.ajax({
                    method: "POST",
                    url: "<?= View::getUrlFromRoute('ajax') ?>",
                    data: data
                }).done(function( msg ) {
                    $("img", thispost).attr('src', '<?= View::getAsset('img/like.png') ?>');
                    $(thispost).removeClass('nolike').addClass('like');
                });
            });
        </script>
        <?php
    }
    ?>
</div>
