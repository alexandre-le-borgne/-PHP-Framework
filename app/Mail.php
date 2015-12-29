<?php

/**
 * Created by PhpStorm.
 * User: julien
 * Date: 29/12/2015
 * Time: 12:00
 */
class Mail
{
    public static function sendMail($user, $email, $key)
    {
        $subject = "Activation de votre compte Aaron";
        $head = "From : inscription@aaron.fr";

        $message = "Bienvenue sur Aaron,

        Pour activer votre compte, merci de cliquer sur le lien suivant ou de le copier/coller dans votre navigateur internet.

        'cuscom.fr/aaron/mailValidation.php?user=$user&key=$key'

        Ce message est automatique, merci de ne pas y repondre.

        L'equipe AaronProject";

        mail($email, $subject, $message, $head);
    }
}