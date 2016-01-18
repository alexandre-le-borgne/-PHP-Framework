<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 11:29
 */
var_dump($_content);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->render('persists/head'); ?>
    </head>
    <body>
        <?= $_content ?>
    </body>
</html>