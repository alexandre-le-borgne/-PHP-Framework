<?php
/**
 * un formulaire d’inscription pour les internautes souhaitant créer un compte permettant au
 * minimum :
 * - d’enregistrer en base de données, obligatoirement, le pseudonyme,
 * - d'enregistrer e-mail et mot de passe (encodé)
 * - d’envoyer un e-mail de confirmation d’inscription à l’internaute ;
 */

$view->extend('layouts/fullpage');
?>

<a id="register_button" href="<?= View::getUrlFromRoute('login') ?>">Connectez-vous !</a>


<div class="section section_home">

    <div class="registerDiv">

        <a href="index"><img id="logo" src="web/img/aaron_text_logo.png"></a>
        <br><br>
        <h4>Nouveau sur Aaron ? Inscrivez-vous ! </h4>

        <!--SIGN UP FORM-->
        <form method="post" action="<?= View::getUrlFromRoute('register') ?>">
            <input class="first" type="text" name="username" placeholder="Pseudonyme" required>
            <input type="email" name="email" placeholder="Email" required pattern="*@-.-">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input class="last" type="password" name="confirmPwd" placeholder="Confirmez votre mot de passe" required>';
            <?php
            if(isset($errors) && $errors != '') {
                echo '<br><div class="errors_fields">'.$errors.'</div>';
            }
            ?>
            <!--SUBMIT ACTION-->
            <input type="submit" value="S'inscrire sur Aaron" class="btn">

            <!--CAPTCHA
            <fieldset id="captchafield">
                <div id="captcha"></div>
            </fieldset>-->

        </form>
    </div>

    <script type="text/javascript">
        /*
         $(function() {
         var s = new Slider("captchafield",{
         message: "Glissez pour créer le compte",
         handler: function(){
         $("#captchafield").hide("slow");
         document.register.submit();
         }
         });
         s.init();
         });
         */
    </script>

</div>