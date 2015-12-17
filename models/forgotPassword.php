<?php
/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 17/12/15
 * Time: 13:36
 */

    $resultat = Database::execute($requete, array(':calories' => 150, ':couleur' => 'red'));

    if($_POST['action'] == 'forgotPwd'){
        $email = Securite::insertBD($_POST['email']);

        $sujet = "Demande nouveau mot de passe";
        $texte = "cc";
    }