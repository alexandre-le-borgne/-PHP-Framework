<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 */
?>
<div class="loginDiv">

    <a href="index"><img id="logo" src="web/img/aaron_text_logo.png"></a>

    <!--SIGN IN FORM-->

    <br><br>
    <form class="form-horizontal" method="post" action="login">
        <div id="fields_errors">
            <!--USERNAME-->
            <input class="first" type="text" name="login" placeholder="Identifiant ou Email" required>
            <!--PASSWORD-->
            <input class="last" type="password" name="password" placeholder="Password" required>
            <input class="errors_fields" type="submit" value="N'oubliez pas votre e-mail !">
            <input class="errors_fields" type="submit" value="N'oubliez pas votre mot de passe !">
            <!--<input class="errors_fields" type="submit" value="Oups ! Votre compte est inexistant !">-->
        </div>
        <!--SUBMIT-->
        <input class="btn" type="submit" value="Connectez-vous !">
        <!--SUBMIT-->
    </form>

    <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>

    <br>
    <h4><img id="logo_explorer" src="web/img/logo_explorer.png"></h4>
    <br>
    <!--SOCIAL CONNECT-->
    <div class="socialConnect">
        <!--            <button type="submit"><img src="web/img/fb_icon_325x325.png" class="img-rounded"></button>-->
        <!--            <button type="submit"><img src="web/img/share-googleplus.png" class="img-rounded"></button>-->
        <?php
        $this->renderControllerAction('google');
        $this->renderControllerAction('facebook');
        ?>
    </div>
</div>

<script>
    $(function () {
        function show_errors(errors) {
            if (errors == true) {
                $('#fields_errors').css('max-height', 'none').css('transition', 'max-height 5s');
                $('.errors_fields').css('display', 'block');
            }
        }
        show_errors(<?php echo (isset($errors) ? 'true' : 'false'); ?>);
    });


</script>