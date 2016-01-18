<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 20:27
 */
?>
<!DOCTYPE html>
<html>
    <?php
    /*
     * La fonction 'render' permet d'inclure une nouvelle vue avec les variables dont elle à besoin.
     * On peut aussi utiliser la fonction 'output' au lieu de la variable si on est pas sûr qu'elle existe :
     * $view->render('exemple/head', array('title' => $view->output('title')));
     * On peut vérifier si l'utilisateur à un certain rang avec la fonction 'isGranted' et avec les constantes de Session.
     * La variable '$_content' contient le code html de la vue remplissant cette template.
     */
    $this->render('exemple/head', array('title' => $title));
    ?>
    <body>
        <?= $_content ?>
    </body>
</html>
