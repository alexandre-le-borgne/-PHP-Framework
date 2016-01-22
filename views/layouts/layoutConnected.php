<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 17:05
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

