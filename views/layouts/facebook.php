<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 18/01/2016
 * Time: 21:54
 */
namespace Facebook;

require 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';

session_start();

$appId = '1695359537375763';
$appSecret = '038f3ed86d0e2e74ab627786aea08c25';

$fb = new Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('alex83690.alwaysdata.net/aaron/fbcallback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';