<?php
/**
un formulaire d’inscription pour les internautes souhaitant créer un compte permettant au

minimum :

- d’enregistrer en base de données, obligatoirement, le nom complet (ou pseudonyme),

- d’envoyer un e-mail de confirmation d’inscription à l’internaute ;

e-mail et mot de passe (encodé) et éventuellement d’autres informations de

l’internaute ;
*/
?>

<form method="post" name="register">
    <div class="formRegister">
        <input type="text" name="username" placeholder="Pseudonyme" required>
    </div>
    <div class="formRegister">
        <input type="email" name="email" placeholder="E-mail" required>
    </div>
    <div class="formRegister">
        <input type="text" name="password" placeholder="Mot de passe" required>
    </div>
    <div class="formRegister">
        <input type="text" name="pwdConfirm" placeholder="Mot de passe" required>
    </div>
    <div class="formRegister">
        <input type="date" name="birthDate" placeholder="Date de naissance" required>
    </div>

    <!-- pour que les bots se trompent -->
    <input type="submit" class="hide">
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