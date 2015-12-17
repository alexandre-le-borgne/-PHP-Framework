<?php
/**
 * Formulaire sur la page d'accueil pour s'enregistrer,
 * Comporte seulement username,email et mot de passe
 * puis redirige vers registerForm, plus complet avec les champs username,email et mot de passe
 * déjà remplis
 */
?>

<div class="preRegisterDiv">

    <h2><strong>Nouveau sur Aaron ?</strong> Inscrivez-vous ! </h2>

    <!--PRE SIGN UP FORM-->
    <form class="form-horizontal" method="post" name="register" action="preregister">
        <!--USERNAME-->
        <div class="control-group">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">@</span>
                    <input class="span2" id="prependedInput" name="username" type="text" placeholder="Username" required>
                </div>
            </div>
        </div>
        <!--EMAIL-->
        <div class="control-group">
            <div class="controls">
                <input type="text" id="inputEmail" name="email" placeholder="Email" required>
            </div>
        </div>
        <!--PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" name="password" placeholder="Mot de passe" required>
            </div>
        </div>
        <!--CONFIRM PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" name="confirmPwd" placeholder="Confirmez votre mot de passe"
                       required>
            </div>
        </div>
        <!--SUBMIT ACTION-->
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="action" value="register" class="btn">S'inscrire sur Aaron</button>
            </div>
        </div>
    </form>
</div>