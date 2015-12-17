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

<!--<div class="registerDiv">-->
<!---->
<!---->
<!---->
<!--    <form id="register" method="post" name="register" action="../../models/Inscription.php">-->
<!---->
<!--        <input type="text" name="username" value="--><?php //echo $username; ?><!--" required>-->
<!--        <input type="email" name="email" value="--><?php //echo $email; ?><!--" required>-->
<!--        <input type="password" name="password" placeholder="Mot de passe" required>-->
<!--        <input type="password" name="pwdConfirm" placeholder="Mot de passe" required>-->
<!--        <input type="date" name="birthDate" placeholder="Date de naissance" required>-->
<!---->
<!--        <button type="submit" name="action" value="register" class="buttonSignup">S'inscrire</button>-->
<!---->
<!--    </form>-->
<!---->
<!--</div>-->

<div class="registerDiv">

    <h2><strong>Finalisez votre inscription !</h2>

    <!--SIGN UP FORM-->
    <form class="form-horizontal" method="post" name="register" action="../../models/Inscription.php">
        <!--USERNAME-->
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="prependedInput" type="text" placeholder="Identifiant" value="<?php echo $username; ?>" required>
        </div>
        <!--EMAIL-->
        <div class="control-group">
            <div class="controls">
                <input type="text" id="inputEmail" placeholder="Email" value="<?php echo $email; ?>" required>
            </div>
        </div>
        <!--PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" placeholder="Mot de passe">
            </div>
        </div>
        <!--CONFIRM PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" placeholder="Confirmer votre mot de passe">
            </div>
        </div>
        <!--BIRTH DATE-->
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="prependedInput" type="date" placeholder="Identifiant" required>
        </div>
        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire</button>
    </form>
</div>


<!--
<input type="hidden" name="register">
<fieldset id="captchafield">
    <div id="captcha"></div>
</fieldset>

</form>

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