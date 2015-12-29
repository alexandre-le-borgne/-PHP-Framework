<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 29/12/2015
 * Time: 14:20
 */

$email = Security::escape($_POST['inputEmail']);

$req = "Select username From User Where email = $email";

$db = new Database();

$user = $db->execute($req);

Mail::sendResetMail($email, $user);

echo "Un mail vous a été envoyé à votre adresse d'inscription, merci de suivre les instructions qu'il renferme";