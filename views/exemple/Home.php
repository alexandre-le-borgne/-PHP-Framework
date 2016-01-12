<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 19:10
 */

namespace exemple;

class Home extends View
{
    public function render($data = array()) {
        var_dump($data);
        ?>
        Exemple Home
        <?php
    }
}