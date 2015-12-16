<?php

    $username = Securite::insertBD($_GET['username']);
    $email = Securite::insertBD($_GET['email']);
    $password = Securite::insertBD($_GET['password']);
    $pwdConfirm = Securite::insertBD($_GET['pwdConfirm']);
    $birthDate = Securite::insertBD($_GET['birthDate']);


    if($_GET['action'] == 'preRegister' && isset($username) && isset($email) && isset($password)) {
        $verifUser = "Select * From maTable Where username = $username";
        $result = Model::excecute($verifUser);

        if($result != NULL){
            echo "Nom d'utilisateur non disponible";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
            echo'Email non valide';
        else{
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
        }

    }
    elseif($_GET['action'] == 'register' && isset($username) && isset($email) && isset($password) && isset($pwdConfirm) && isset($birthDate)){
        if ($password == $pwdConfirm){
            $insert = "Insert Into maTable ('username', 'email', 'password', 'pwdConfirm', 'birthDate') VALUES ('$username', '$email', '$password', '$birthDate')";
            $result = Model::excecute($sql);
        }
        else {
            echo 'Le mot de passe et la confirmation doivent etre identiques';
            header('Location: registerForm.php');
        }

    }
    else{
        echo '<br/><strong>Veuillez remplir tous les champs</strong><br/>';
    }
