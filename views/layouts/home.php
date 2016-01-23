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
        $this->render('persists/categories');
        $this->render('persists/feed');
        ?>
    </div>


<?php
echo $this->output('_content');
