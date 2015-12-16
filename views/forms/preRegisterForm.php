<?php
/**
 * Formulaire sur la page d'accueil pour s'enregistrer,
 * Comporte seulement username,email et mot de passe
 * puis redirige vers registerForm, plus complet avec les champs username,email et mot de passe
 * déjà remplis
 *
 */
?>

<div class="preRegisterDiv">

    <h2><strong>Nouveau sur Aaron ?</strong> Inscrivez-vous ! </h2>

    <form class="preRegisterForm" method="post" name="register" action="index.php?controller=index&action=register">

        <input type="text" name="username" placeholder="Pseudonyme" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="action" value="preRegister" class="buttonSignup">S'inscrire sur Aaron</button>

    </form>

</div>