<?php
/**
 * Formulaire de remise a zero du mot de passe
 */
    session_start();
    var_dump($_GET['user']);
    var_dump($_GET['key']);
    $_SESSION['user'] = $_GET['user'];
    $_SESSION['key'] = $_GET['key']; ?>

<div class="resetDiv">

    <h2><strong>Réinitialisez votre mot de passe !</h2>

    <!--RESET FORM-->
    <form class="form-horizontal" method="post" name="reset" action="<?= View::getUrlFromRoute('resetpassword') ?>">

        <input class="last" type="password" name="password" placeholder="Password" required>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="reset" class="btn">Changer le mot de passe</button>


    </form>
</div>