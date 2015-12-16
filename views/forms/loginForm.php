<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 *
 */
?>

<div class="loginDiv"><!-- w=302, h=119 -->

    <form class="loginForm" method="post" name="register"><!-- w=276, h=99 -->

        <div class="field"><!-- w=276, h=30  + margin-bottom=? -->
            <input type="text" name="username" placeholder="Pseudonyme" required>
        </div>

        <div class="field">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="buttonSignup">Se connecter</button>
        </div>

        <div class="rememberAndForgot">
            <label class="remember">
                <input type="checkbox" value="1" name="rememberMe">
                <span>Se souvenir de moi</span>
            </label>
            <span class="separator">·</span>
            <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>
        </div>

    </form>

    <div class="socialConnect">

        <button type="submit">Fb</button>
        <button type="submit">Tw</button>
        <button type="submit">Gg</button>

    </div>

</div>