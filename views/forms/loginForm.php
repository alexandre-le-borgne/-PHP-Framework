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
    <form class="form-horizontal" method="post" name="register">
        <div class="control-group">
            <div class="controls">
                <input type="text" id="inputEmail" placeholder="Email">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" placeholder="Password">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox"> Remember me
                </label>
                <button type="submit" class="btn">Sign in</button>
            </div>
        </div>
    </form>
</div>

    <div class="socialConnect">

        <button type="submit">Fb</button>
        <button type="submit">Tw</button>
        <button type="submit">Gg</button>

    </div>

</div>
<br>