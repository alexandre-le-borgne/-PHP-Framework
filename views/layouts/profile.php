<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 02:47
 */
$view->extend('layouts/layout');
$this->render('persists/header');

var_dump($categories);


foreach ($categories as $category)
{
    echo $category['title'];
    echo '<br/>';

    $cat = $category['categories'];
    var_dump($cat['twitter']);
    var_dump($cat['email']);
    var_dump($cat['rss']);

    echo '<br/>';
}