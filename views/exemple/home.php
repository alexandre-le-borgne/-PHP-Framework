<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 19:10
 */

/*
 * La fonction 'extend' permet de signaler que cette vue ira remplir un morceau manquant de la template qui lui est
 * passé en paramètre.
 */

View::extend('exemple/body');
?>
<h3>Contenu</h3>
<pre>
    <?= View::render('exemple/content') ?>
</pre>
<h3>Contenu pouvant ne pas avoir été précisé avec une valeur par défaut</h3>
<pre>
    <?= View::output('content2', 'Mon contenu par défaut') ?>
</pre>
<h3>Contenu protégé</h3>
<pre>
    <?= View::escape($content) ?>
</pre>
