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
        <!--EMAIL-->
        <input type="text" id="inputEmail" name="email" placeholder="Email" required><br>

        <!--PASSWORD-->
        <input type="password" id="inputPassword" name="password" placeholder="Mot de passe" required><br>

        <!--CONFIRM PASSWORD-->
        <input type="password" id="inputPassword" name="confirmPwd" placeholder="Confirmez votre mot de passe" required><br>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire sur Aaron</button>

    </form>
</div>