<?php
$view->extend('layouts/layout');
$this->render('persists/header');
?>
    <div id="layout_connected">
        <?php
        var_dump($article);
        $this->render('persists/feed', array('articles' => $article));
        ?>
    </div>
<?php