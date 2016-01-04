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
        if(isset($errors['email'])) { ?>
            <div class="control-group info">
              <label class="control-label" for="email">Input with info</label>
              <div class="controls">
                <input type="text" id="inputInfo">
                <span class="help-inline"><?php $errors['email']?></span>
              </div>
            </div>
        <?php
        }
        else{
            ?>
            <input type="text" id="inputEmail inputInfo" name="email" placeholder="Email" required><br><br>
        <?php
        }
        ?>

        <?php ?>

        <!--PASSWORD-->
        <input type="password" id="inputPassword" name="password" placeholder="Mot de passe" required><br><br>

        <!--CONFIRM PASSWORD-->
        <input type="password" id="inputPassword" name="confirmPwd" placeholder="Confirmez votre mot de passe" required><br><br>

        <!--SUBMIT ACTION-->
        <button type="submit" name="action" value="register" class="btn">S'inscrire sur Aaron</button>

    </form>
</div>