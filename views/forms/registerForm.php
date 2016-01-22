<?php
/**
 * un formulaire d’inscription pour les internautes souhaitant créer un compte permettant au
 * minimum :
 * - d’enregistrer en base de données, obligatoirement, le pseudonyme,
 * - d'enregistrer e-mail et mot de passe (encodé)
 * - d’envoyer un e-mail de confirmation d’inscription à l’internaute ;
 */

$view->extend('layouts/section_home');
?>

<div class="registerDiv">

    <a href="index"><img id="logo" src="web/img/aaron_text_logo.png"></a>
<br><br>
    <h4>Nouveau sur Aaron ? Inscrivez-vous ! </h4>

    <!--SIGN UP FORM-->
    <form method="post" action="register">
        <input type="text" name="username" placeholder="Pseudonyme" required>

        <!--BIRTH DATE
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="prependedInput" type="date" name="birthDate" placeholder="Date de naissance" required>
        </div>-->

        <!--EMAIL-->
        <?php
        if (isset($errors['email'])) { ?>
            <div class="control-group info">
                <input type="email" name="email" placeholder="Email" required pattern="*@-.-">
                <span class="help-inline"><?php echo $errors['email'] ?></span>
            </div>
            <?php
        } else
            echo '<input type="email" name="email" placeholder="Email" required pattern="*@-.-"';
        ?>

        <?php //Les div de confirmation de password, on lui redonne les erreurs si presentes
        $this->render('forms/passwordConfirmForm', (isset($errors) ? array("errors" => $errors) : null))
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