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

    <!--FORGOT PWD FORM-->
    <form class="form-horizontal" method="post" name="register" action="mailReset">
        <!--E-MAIL-->
        <div class="control-group">
            <div class="controls">
                <input type="email" name="email" id="inputEmail" placeholder="Email">
            </div>
        </div>
        <!--SUBMIT-->
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="action" value="forgotPwd" class="btn">Réinitialiser</button>
            </div>
        </div>
    </form>
</div>
