<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 29/12/2015
 * Time: 14:20
 */

$email = Security::escape($_POST['inputEmail']);

$req = "Select username, userKey From User Where email = $email";

$db = new Database();

$data = $db->execute($req)->fetch();

$user = $data['username'];
$key = $data['userKey'];

Mail::sendResetMail($email, $user, $key);

echo "Un mail vous a été envoyé à votre adresse d'inscription, merci de suivre les instructions qu'il renferme";