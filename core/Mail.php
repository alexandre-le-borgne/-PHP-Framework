<?php

class Mail
{
    public static function sendVerificationMail($user, $email, $key)
    {
        $subject = "Activation de votre compte Aaron";
        $head = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $head .= "From : inscription@aaron.fr";

        $message = "
        <html>
            <body>
                <p>Bienvenue sur Aaron, <br/>

                Pour activer votre compte, merci de cliquer sur le lien suivant <br/></p>

                <a href='http://alex83690.alwaysdata.net/aaron/mailvalidation/$user/$key'>Inscrivez vous !</a>

                <p>Ce message est automatique, merci de ne pas y repondre.

                L'equipe AaronProject</p>
            </body>
        </html>";

        mail($email, $subject, $message, $head);
    }

    public static function sendWelcomingMail($email)
    {
        $subject = "Activation de votre compte Aaron";
        $head = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $head .= "From : welcome@aaron.fr";

        $message = "
        <html>
            <body><p>Merci d'avoir validé votre inscription !

                    Vous pouvez désormais suivre vos actualités à partir de votre page personnelle.

                    Ce message est automatique, merci de ne pas y repondre.

                    L'equipe AaronProject</p>
            </body>
        </html>";

        mail($email, $subject, $message, $head);
    }

    public static function sendForgotMail($email, $user, $key)
    {
        $subject = "Oubli de votre mot de passe Aaron";
        $head = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $head .= "From : reset@aaron.fr";

        $message = "
        <html>
            <body>
                <p>Une demande de réinitialisation de mot de passe vient d'être effectuée.<br/>
                   Supprimez immédiatement de message si vous n'en êtes pas l'auteur.<br/>
                   Sinon, merci de cliquer sur le lien suivant ou de le copier/coller dans votre navigateur internet.</p>

                <a href='http://alex83690.alwaysdata.net/aaron/resetForm/$user/$key'>Réinitialisation</a>


                <p>Ce message est automatique, merci de ne pas y repondre.

                L'equipe AaronProject</p>";

        mail($email, $subject, $message, $head);
    }

}