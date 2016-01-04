<?php
/**
 *
 *
 */


$email = Security::escape($_POST['inputEmail']);

$req = "Select username, userKey From User Where email = $email";

$db = new Database();

$data = $db->execute($req)->fetch();

$user = $data['username'];
$key = $data['userKey'];

Mail::sendResetMail($email, $user, $key);

echo "Un mail vous a été envoyé à votre adresse d'inscription, merci de suivre les instructions qu'il renferme";