<?php
/**
 * Formulaire sur la page d'accueil pour s'enregistrer,
 * Comporte seulement username,email et mot de passe
 * puis redirige vers registerForm, plus complet avec les champs username,email et mot de passe
 * déjà remplis
 */
?>

<div class="preRegisterDiv">


    <h4>Nouveau sur Aaron ? Inscrivez-vous ! </h4>

    <!--PRE SIGN UP FORM-->
    <form class="form-horizontal" method="post" name="register" action="preregister">

        <!--EMAIL-->
        <?php
        if (isset($errors['email'])) { ?>
            <div class="control-group info">
                <input type="email" id="inputEmail inputInfo" name="email" placeholder="Email" required pattern="*@-.-"><br><br>
                <span class="help-inline"><?php echo $errors['email'] ?></span>
            </div>
        <?php
        } else
            echo '<input type="email" id="inputEmail inputInfo" name="email" placeholder="Email" required pattern="*@-.-"><br><br>';
        ?>

        <?php //Les div de confirmation de password
        View::getView("forms/passwordConfirm", ($errors ? $errors : null));
        ?>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire sur Aaron</button>

    </form>
</div>