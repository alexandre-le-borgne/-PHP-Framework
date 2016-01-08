    <!--PASSWORD-->
<?php
if (isset($errors['password']))
{ ?>
    <div class="control-group error">
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <span class="help-inline"><?php echo $errors['password'] ?></span>
    </div>
<?php
}
else
    echo '<input type="password" name="password" placeholder="Mot de passe" required><br><br>';
?>

    <!--CONFIRM PASSWORD-->
<?php
if (isset($errors['password']))
{ ?>
    <div class="control-group error">
        <input type="password" name="confirmPwd" placeholder="Confirmez votre mot de passe" required><br><br>
        <span class="help-inline"><?php echo $errors['password'] ?></span>
    </div>
<?php
}
else
    echo '<input type="password" name="confirmPwd" placeholder="Confirmez votre mot de passe" required><br><br>';



/*

<!--PASSWORD-->
<div class="control-group">
    <div class="controls">
        <div class="input-prepend">
            <span class="add-on">@</span>
            <input class="span2" id="resetPwd" type="password" name="password" placeholder="mot de passe" required>
        </div>
    </div>
</div>

<!--CONFIRMATION-->
<div class="input-prepend">
    <span class="add-on">@</span>
    <input class="span2" id="resetConfirm" type="password" name="confirmPwd" placeholder="confirmation" required>
</div>

 */

