<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 */
$view->extend('layouts/fullpage');
?>

<a id="register_button" href="<?= View::getUrlFromRoute('register') ?>">Inscrivez-vous !</a>


<div class="section section_home">

    <div class="loginDiv">

        <a href="index"><img id="logo" src="<?= View::getAsset('img/aaron_text_logo.png') ?>"></a>

        <!--SIGN IN FORM-->

        <br><br>

        <form class="form-horizontal" method="post" action="<?= View::getUrlFromRoute('login') ?>">
            <div id="fields_errors">
                <!--USERNAME-->
                <input class="first" type="text" name="login" placeholder="Identifiant ou Email" required>
                <!--PASSWORD-->
                <input class="last" type="password" name="password" placeholder="Password" required>
                <?php
                if (isset($errors) && $errors != '')
                {
                    echo '<br><div class="errors_fields">' . $errors . '</div>';
                }
                ?>
            </div>
            <!--SUBMIT-->
            <input class="btn" type="submit" value="Connectez-vous !">
            <!--SUBMIT-->
        </form>

        <a class="forgot" href="<?= View::getUrlFromRoute('forgotForm') ?>">Mot de passe oublié ?</a>

        <br>
        <h4><img id="logo_explorer" src="<?= View::getAsset('img/logo_explorer.png') ?>"></h4>
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
</div>

<script>
//    $(document).ready(function() {
//        // $(window).resize() est appelée chaque fois que la fenêtre est redimensionnée par l'utilisateur.
//        $(window).resize(function() {
//            $(".loginDiv").css({
//                position:'absolute',
//                left:($(window).width() - $(".loginDiv").outerWidth()) / 2,
//                top:($(window).height() - $(".loginDiv").outerHeight()) / 2
//            });
//        });
//    });
//
//    $(window).load(function() {
//        // au chargement complet de la page, la fonction resize() est appelée une fois pour initialiser le centrage.
//        $(window).resize();
//    });
</script>