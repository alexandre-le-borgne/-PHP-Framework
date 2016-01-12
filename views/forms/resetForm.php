<?php
/**
 * Formulaire de remise a zero du mot de passe
 */

//$user = $_GET['user'];
//$oldKey = $_GET['key']; ?>

<div class="resetDiv">

    <h2><strong>Réinitialisez votre mot de passe !</h2>

    <!--RESET FORM-->
    <form class="form-horizontal" method="post" name="reset" action="passwordReset">

        <?php //Les div de confirmation de password, on lui redonne les erreurs si presentes
        AbstractView::getView("forms/passwordConfirm", (isset($errors) ? $data = array("errors" => $errors) : null));
        ?>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="reset" class="btn">Changer le mot de passe</button>


    </form>
</div>