  GoogleAuth PHP Library Documentation

GoogleAuth PHP Library Documentation
====================================

Introduction
------------

GoogleAuth is a PHP library developed by Rohit Chouhan for integrating Google OAuth authentication into your web applications. It simplifies the process of handling OAuth authorization, token generation, and user information retrieval.

Table of Contents
-----------------

*   [Installation](#installation)
*   [Usage](#usage)
    *   [Initialization](#initialization)
    *   [Setting Redirect URI](#setting-redirect-uri)
    *   [Setting OAuth Scopes](#setting-oauth-scopes)
    *   [Getting OAuth Login URL](#getting-oauth-login-url)
    *   [Getting Access Token](#getting-access-token)
    *   [Resetting Access Token](#resetting-access-token)
    *   [Getting User Information](#getting-user-information)

Installation
------------

Clone the repository from [GitHub](https://github.com/rohit-chouhan/GoogleAuth) or download the `GoogleAuth.php` file and include it in your project.

Usage
-----

### Initialization
This function initializes the GoogleAuth class with the provided OAuth client ID and client secret.

```php
<?php
    session_start();
    require_once('GoogleAuth.php');
    
    // Your OAuth client ID and client secret from Google Cloud Console
    $clientID = 'YOUR_CLIENT_ID';
    $clientSecret = 'YOUR_CLIENT_SECRET';
    
    $googleAuth = new GoogleAuth($clientID, $clientSecret);
```    

### Setting Redirect URI
This function sets the redirect URI for OAuth authorization. It should match the redirect URI registered in the Google Cloud Console.

```php
$redirectURI = 'YOUR_REDIRECT_URI';
$googleAuth->setRedirectURI($redirectURI);
```

### Setting OAuth Scopes
This function sets the OAuth scopes required for authorization. It takes an array of OAuth scopes as a parameter.

```php
$scope = ['email', 'profile']; // Example scopes
$googleAuth->setScope($scope);
```  

### Getting OAuth Login URL
This function generates the OAuth login URL. Users are redirected to this URL to authorize the application.

```php
$authURL = $googleAuth->getLoginURI();
```   

### Getting Access Token
This function returns the access token received after user login for an access token, you don't need to refresh your token, this function can refresh access token every time, so user don't need to login everytime.

```php
$code = $_GET['code']; // Authorization code received after user login
$accessToken = $googleAuth->getAccessToken($code);
```  

### Resetting Access Token
This function resets the stored access token. It can be used to perform a debug login.

```php
$googleAuth->resetAccessToken();
```  

### Getting User Information
This function retrieves user information using the stored access token. It returns an object containing user details such as name and email address.

```php
$userInfo = $googleAuth->getUserInfo();
echo "User Name: " . $userInfo->name;
echo "User Email: " . $userInfo->email;
```

### Complete Example Code
```php
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

```

For more details, refer to the [GoogleAuth GitHub Repository](https://github.com/rohit-chouhan/GoogleAuth).
