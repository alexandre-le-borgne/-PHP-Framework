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

    <form id="register" method="post" name="register" action="../../models/Inscription.php">

            <input type="text" name="username" value="password" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="pwdConfirm" placeholder="Mot de passe" required>
            <input type="date" name="birthDate" placeholder="Date de naissance" required>

        <input type="submit" name="action" value="register" >

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