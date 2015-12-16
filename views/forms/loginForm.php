<?php
/**
 * un formulaire d’authentification par e-mail et mot de passe (et, optionnellement : avec un
 *
 * compte Google, Facebook, Twitter, etc.) ;
 */
?>

<!-- w=302, h=119 -->
<div class="loginDiv">

    <!-- w=276, h=99 -->
    <form class="loginForm" method="post" name="register">

        <!-- w=276, h=30  + margin-bottom=? -->
        <input type="text" name="username" placeholder="Pseudonyme" required>

        <!-- w=276, h=30  + margin-top=? -->
        <input type="text" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="buttonSignup">Connexion</button>

        <div class="rememberAndForgot">
            <label class="remember">
                <input type="checkbox" value="1" name="rememberMe">
                <span>Se souvenir de moi</span>
            </label>
            <span class="separator">·</span>
            <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>
        </div>

    </form>

</div>