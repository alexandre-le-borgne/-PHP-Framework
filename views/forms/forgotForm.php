<?php
/**
 * un système de mot de passe oublié ou perdu pour réinitialiser mot de passe
 * par mail.
 * generation automatique d'un nouveau mot de passe, l'utilisateur pourra le changer
 * sur sa gestion de compte.
 */
?>

<div class="forgotDiv">
    <h2><strong>Retrouvez votre mot de passe !</strong></h2>

    <form class="form-horizontal" method="post" name="register">
        <div class="control-group">
            <div class="controls">
                <input type="email" id="inputEmail" placeholder="Email">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="action" value="forgotPwd" class="btn">Sign in</button>
            </div>
        </div>
    </form>
</div>
