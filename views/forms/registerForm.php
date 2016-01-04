<?php
/**
 * un formulaire d’inscription pour les internautes souhaitant créer un compte permettant au
 * minimum :
 * - d’enregistrer en base de données, obligatoirement, le pseudonyme,
 * - d'enregistrer e-mail et mot de passe (encodé)
 * - d’envoyer un e-mail de confirmation d’inscription à l’internaute ;
 *
 */
?>

<div class="registerDiv">

    <h2><strong>Finalisez votre inscription !</h2>

    <!--SIGN UP FORM-->
    <form class="form-horizontal" method="post" name="register" action="../../modelsAVirer/Inscription.php">
        <!--USERNAME-->
        <div class="control-group">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">@</span>
                    <input class="span2" id="prependedInput" type="text" name="username" placeholder="Pseudonyme" required>
                </div>
            </div>
        </div>

        <!--BIRTH DATE-->
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="prependedInput" type="date" placeholder="Date de naissance" required>
        </div>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire</button>

        <!--CAPTCHA-->

        <input type="text" name="register">
        <fieldset id="captchafield">
            <div id="captcha"></div>
        </fieldset>

    </form>
</div>



<script type="text/javascript">
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
</script>