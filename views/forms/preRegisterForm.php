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

<div class="preRegisterDiv">

    <h2><strong>Nouveau sur Aaron ?</strong> Inscrivez-vous ! </h2>

    <form class="preRegisterForm" method="post" name="register">
        <div class="registerInput">
            <input type="text" name="username" placeholder="Pseudonyme" required>
        </div>
        <div class="registerInput">
            <input type="email" name="email" placeholder="E-mail" required>
        </div>
        <div class="registerInput">
            <input type="text" name="password" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="buttonSignup">S'inscrire sur Aaron</button>

    </form>

</div>


<div class="front-signup js-front-signup">
    <h2><strong>Nouveau sur Twitter ?</strong> Inscrivez-vous</h2>

    <form action="https://twitter.com/signup" class="t1-form signup" id="frontpage-signup-form" method="post">

        <div class="field">
            <input type="text" class="text-input" autocomplete="off" name="user[name]" maxlength="20"
                   placeholder="Nom complet">
        </div>
        <div class="field">
            <input type="text" class="text-input email-input" autocomplete="off" name="user[email]"
                   placeholder="Adresse email">
        </div>
        <div class="field">
            <input type="password" class="text-input" name="user[user_password]" placeholder="Mot de passe">
        </div>

        <input type="hidden" value="" name="context">
        <input type="hidden" value="ad507ee0b360c2c5e7400acb480ceaaac37beb31" name="authenticity_token">
        <button type="submit" class="btn signup-btn u-floatRight">
            S'inscrire sur Twitter
        </button>
    </form>
</div>