<?php
/**
 *
 * Formulaire sur la page d'accueil pour s'enregistrer,
 *
 * Comporte seulement username,email et mot de passe
 *
 * puis redirige vers registerForm, plus complet avec les champs username,email et mot de passe
 * déjà remplis
 *
 */
include("../persists/head.php");

?>

<form id="preRegister" method="post" name="register">
    <div class="formRegister">
        <input type="text" name="username" placeholder="Pseudonyme" required>
    </div>
    <div class="formRegister">
        <input type="email" name="email" placeholder="E-mail" required>
    </div>
    <div class="formRegister">
        <input type="text" name="password" placeholder="Mot de passe" required>
    </div>

    <button type="submit" class="buttonSignup">S'inscrire sur Aaron</button>

</form>