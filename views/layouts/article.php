<?php
$view->extend('layouts/layout');
$this->render('persists/header');
?>
    <div id="layout_connected">
        <?php
        $this->render('persists/feed', array('articles' => $article));
        ?>
    </div>
<?php