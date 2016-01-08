<?php
/**
 * Validation du mail (Model ?)
 */

$user = $_GET['user'];
$key = $_GET['key'];

$db = new Database();
$req = "Select email, userKey, active From accounts Where username like :user";

if($db->execute($req, $user) && $data = $db->execute($req, $user)->fetch())
{
    $email = $data['email'];
    $realKey = $data['userKey'];
    $active = $data['active'];
}

if($active == 1)
    echo "Votre compte est déjà actif";
else
{
    if($key == $realKey)
    {
        echo "Votre compte a bien été activé";
        $req = "Update accounts Set active = 1 Where username like :user";
        $db->execute($req, $user);
        Mail::sendWelcomingMail($email);
    }
    else
        echo "Erreur : aucun compte n'est associé à cette adresse";
}