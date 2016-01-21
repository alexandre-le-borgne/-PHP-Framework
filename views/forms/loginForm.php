<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 */
?>
<div class="loginDiv">

    <h4>Connectez-vous ?</h4>
    <!--SIGN IN FORM-->

    <form class="form-horizontal" method="post" action="login">
        <!--USERNAME-->
        <input type="text" name="login" placeholder="Identifiant ou Email"><br><br>
        <!--PASSWORD-->
        <input type="password" name="password" placeholder="Password"><br><br>
        <!--SUBMIT-->
        <input class="btn" type="submit" value="Submit">
        <!--SUBMIT-->
    </form>

    <label class="checkbox">
        <input type="checkbox"> Remember me
    </label>
    <a class="forgot" href="forgotForm">Mot de passe oublié ?</a>
    <hr>
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