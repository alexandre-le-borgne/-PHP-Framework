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

?>

<form id="register" method="post" name="register">
    <div class="formRegister">
        <input type="text" name="username" placeholder="Pseudonyme" required>
    </div>
    <div class="formRegister">
        <input type="email" name="email" placeholder="E-mail" required>
    </div>
    <div class="formRegister">
        <input type="text" name="password" placeholder="Mot de passe" required>
    </div>

    <input type="submit" name="action" value="register">
    <button type="submit" class="buttonSignup">S'inscrire</button>

</form>