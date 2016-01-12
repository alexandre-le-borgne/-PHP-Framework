<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 19:10
 */
$this->extend("exemple/layout");
?>
<h3>Contenu</h3>
<pre>
    <?= $content ?>
</pre>
<h3>Contenu pouvant ne pas avoir été précisé avec une valeur par défaut</h3>
<pre>
    <?= $view->output('content', 'Mon contenu par défaut') ?>
</pre>
<h3>Contenu protégé</h3>
<pre>
    <?= $view->escape($content) ?>
</pre>
