<?php
/**
 * Formulaire sur la page d'accueil pour s'enregistrer,
 * Comporte seulement username,email et mot de passe
 * puis redirige vers registerForm, plus complet avec les champs username,email et mot de passe
 * déjà remplis
 *
 */
?>



<!--    <h2><strong>Nouveau sur Aaron ?</strong> Inscrivez-vous ! </h2>-->
<!---->
<!--    <form class="preRegisterForm" method="post" name="register" action="index.php?controller=index&action=register">-->
<!---->
<!--        <input type="text" name="username" placeholder="Pseudonyme" required>-->
<!--        <input type="email" name="email" placeholder="E-mail" required>-->
<!--        <input type="password" name="password" placeholder="Mot de passe" required>-->
<!--        <button type="submit" name="action" value="preRegister" class="buttonSignup">S'inscrire sur Aaron</button>-->
<!---->
<!--    </form>-->

<div class="preRegisterDiv">

    <h2><strong>Nouveau sur Aaron ?</strong> Inscrivez-vous ! </h2>

    <!--PRE SIGN UP FORM-->
    <form class="form-horizontal" method="post" name="register" action="index.php?controller=index&action=register">
        <!--USERNAME-->
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="prependedInput" type="text" placeholder="Identifiant" required>
        </div>
        <!--EMAIL-->
        <div class="control-group">
            <div class="controls">
                <input type="text" id="inputEmail" placeholder="Email" required>
            </div>
        </div>
        <!--PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <input type="password" id="inputPassword" placeholder="Mot de passe">
            </div>
        </div>
        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire sur Aaron</button>
    </form>
</div>