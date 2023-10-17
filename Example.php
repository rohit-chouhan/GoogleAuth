<?php
session_start();
/** 
* Session start is required to save user token, so user don't need to login every time
* this library can help you to refresh token automatically
*/

/**
 * Make sure this page is as your redirect URI
 * Ex. if this page is example.com/login.php same url should be in Redirect URL in Console Cloud
 */

include "GoogleAuth.php";

$clientID = ""; //Client ID Here
$clientSecret = ""; //Client Secret Code Here
$redirectURI = ""; //Registered Redirect URL Here
$scope = ["profile", "email"]; //Scopes

$google_auth = new GoogleAuth($clientID,$clientSecret);
$google_auth->setRedirectURI($redirectURI);
$google_auth->setScope($scope);

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $userInfo = $google_auth->getUserInfo();
    echo '<img src="'. $userInfo->picture.'"/>';
    echo '<br><br>';
    echo "Name: " .  $userInfo->name;
    echo '<br><br>';
    echo "Email: " .  $userInfo->email;
    echo '<br><br>';
    echo "Access Token: " . $google_auth->getAccessToken($code);
} else {
    echo "<h2>Login to Continue</h2>";
    echo '<a href="' . $google_auth->getLoginURI() . '"><img src="https://developers.google.com/static/identity/images/btn_google_signin_dark_normal_web.png"/></a>';
}
