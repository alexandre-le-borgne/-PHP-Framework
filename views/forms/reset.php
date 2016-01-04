<?php
/**
 * Formulaire de remise a zero du mot de passe
 */

//$user = $_GET['user'];
//$oldKey = $_GET['key']; ?>

<div class="resetDiv">

    <h2><strong>Réinitialisez votre mot de passe !</h2>

    <!--RESET FORM-->
    <form class="form-horizontal" method="post" name="reset" action="/*TODO*/">
        <!--PASSWORD-->
        <div class="control-group">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">@</span>
                    <input class="span2" id="resetPwd" type="password" placeholder="mot de passe" required>
                </div>
            </div>
        </div>

        <!--CONFIRMATION-->
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="resetConfirm" type="password" placeholder="confirmation" required>
        </div>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="reset" class="btn">Changer le mot de passe</button>


    </form>
</div>