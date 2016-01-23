<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/01/2016
 * Time: 14:07
 */
$view->extend('layouts/layout');
$this->render('persists/header');
?>
    <div id="layout_connected">
        <?php
        $this->render('persists/feed', array('articles' => $articles));
        $this->render('persists/categories', array('categories' => $categories));
        ?>
    </div>
<?php