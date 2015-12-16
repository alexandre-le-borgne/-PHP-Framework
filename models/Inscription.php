<?php

    $username = Securite::insertBD($_POST['username']);
    $email = Securite::insertBD($_POST['email']);
    $password = crypt(Securite::insertBD($_POST['password']), CRYPT_SHA512);
    $pwdConfirm = crypt(Securite::insertBD($_POST['pwdConfirm']), CRYPT_SHA512);
    $birthDate = Securite::insertBD($_POST['birthDate']);


    if($_POST['action'] == 'preRegister' && isset($username) && isset($email) && isset($password)) {
        $verifUser = "Select * From maTable Where username = $username";
        $result = Model::excecute($verifUser);

        if($result == NULL){
            echo "Nom d'utilisateur non disponible, redirection vers l'inscription";
            header("refresh:3; url=../views/forms/registerForm.php");
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Email non valide, redirection vers l\'inscription';
            header("refresh:3; url=../views/forms/registerForm.php");
        }
        else{
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header("Location: '../views/forms/registerForm.php");
        }
    }
    elseif($_POST['action'] == 'register' && isset($username) && isset($email) && isset($password) && isset($pwdConfirm) && isset($birthDate)){
        if ($password == $pwdConfirm){
            $insert = "Insert Into maTable ('username', 'email', 'password', 'pwdConfirm', 'birthDate') VALUES ('$username', '$email', '$password', '$birthDate')";
            $result = Model::excecute($sql);
        }
        else {
            echo 'Le mot de passe et la confirmation doivent etre identiques';
            header('Location: ../views/forms/registerForm.php');
        }

    }
    else{
        echo '<br/><strong>Veuillez remplir tous les champs</strong><br/>';
    }
