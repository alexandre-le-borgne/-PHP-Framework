<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 02:47
 */
$view->extend('layouts/layout');
$this->render('persists/header');

//var_dump($categories);


foreach ($categories as $category)
{
    echo $category['title'];
    echo '<br/>';

    foreach($category['categories'] as $cat)
    {
        var_dump($cat);
        echo '<br/>';
    }
}