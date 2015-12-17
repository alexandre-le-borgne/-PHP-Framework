<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 *
 */
?>

<!-- w=302, h=119 -->
<!---->
<!--            <input type="text" name="username" placeholder="Pseudonyme" required>-->
<!--            <input type="password" name="password" placeholder="Mot de passe" required>-->
<!--            <button type="submit" class="buttonSignup">Se connecter</button>-->
<!---->
<!---->
<!--        <div class="rememberAndForgot">-->
<!--            <label class="remember">-->
<!--                <input type="checkbox" value="1" name="rememberMe">-->
<!--                <span>Se souvenir de moi</span>-->
<!--            </label>-->
<!--            <span class="separator">·</span>-->
<!--            <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>-->
<!--        </div>-->
<!---->
<!--    </form>-->

<div class="loginDiv">

    <h2>Connectez-vous ?</h2>

    <!--SIGN IN FORM-->
    <form class="form-horizontal" method="post" name="register">
        <!--USERNAME-->

            <div class="controls">
                <input type="text" placeholder="Identifiant">
            </div>
            <div class="controls">
                <input type="password" id="inputPassword" placeholder="Password">
            </div>
            <div class="controls">
                <button type="submit" class="btn">Sign in</button>
            </div>
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox"> Remember me
                </label>
                <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>
            </div>

        <hr>
        <!--SOCIAL CONNECT-->
        <div class="socialConnect">
<!--            <button type="submit"><img src="web/img/fb_icon_325x325.png" class="img-rounded"></button>-->
<!--            <button type="submit"><img src="web/img/share-googleplus.png" class="img-rounded"></button>-->
            <button class="btn btn-large btn-block btn-primary" type="button">Block level button</button>
            <button class="btn btn-large btn-block" type="button">Block level button</button>
        </div>
    </form>
</div>