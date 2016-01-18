<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 18/01/2016
 * Time: 21:54
 */
namespace Facebook;
use Facebook\Facebook;

session_start();

$appId = '1695359537375763';
$appSecret = '038f3ed86d0e2e74ab627786aea08c25';

$fb = new Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.2',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://alex83690.alwaysdata.net/aaron/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';