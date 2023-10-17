<?php
 /**
     * Google Auth 1.0
     * Developer : Rohit Chouhan
     * https://github.com/rohit-chouhan/GoogleAuth
*/

class GoogleAuth {

    private $clientID; // Your OAuth client ID from Google Cloud Console
    private $clientSecret; // Your OAuth client secret from Google Cloud Console
    private $redirectURI; // Your redirect URI registered in Google Cloud Console
    private $authorizationEndpoint = 'https://accounts.google.com/o/oauth2/auth';
    private $tokenEndpoint = 'https://oauth2.googleapis.com/token';
    private $scope;

    function __construct(string $clientID, string $clientSecret){
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
    }

     /**
     * Constructor to set clientID and clientSecret.
     *
     * @param string $clientID     Your OAuth client ID from Google Cloud Console
     * @param string $clientSecret Your OAuth client secret from Google Cloud Console
     */
    public function setRedirectURI(string $redirectURI){
        $this->redirectURI = $redirectURI;
    }

      /**
     * Set OAuth scopes.
     *
     * @param array $scope Array of OAuth scopes
     */
    public function setScope(array $scope){
        $this->scope = implode(" ",$scope);
    }

      /**
     * Get the Google OAuth login URL.
     *
     * @return string OAuth login URL
     */
    public function getLoginURI(){
        $authURL = "$this->authorizationEndpoint?client_id=$this->clientID&redirect_uri=$this->redirectURI&response_type=code&scope=$this->scope";
        return $authURL;
    }

     /**
     * Get access token using the authorization code.
     *
     * @param string $code Authorization code received after user login
     *
     * @return string Access token
     */
    public function getAccessToken($code){
        $tokenRequestData = [
            'code' => $code,//
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectURI,//
            'grant_type' => 'authorization_code'
        ];

        if(!empty($_SESSION['recheck_google_auth_token'])){
            unset($tokenRequestData['code']);
            unset($tokenRequestData['redirect_uri']);
            $tokenRequestData['grant_type']='refresh_token';
            $tokenRequestData['refresh_token']=$_SESSION['recheck_google_auth_token'];
        }
        
        $ch = curl_init($this->tokenEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenRequestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $tokenResponse = curl_exec($ch);
        curl_close($ch);
        
        $tokenData = json_decode($tokenResponse, true);

        
        if(empty($_SESSION['recheck_google_auth_token'])){
            $_SESSION['recheck_google_auth_token'] = $tokenData['access_token'];
        }
        return $tokenData['access_token'];
        
    }

      /**
     * Reset the stored access token, or debug login
     */
    public function resetAccessToken(){
        unset($_SESSION['recheck_google_auth_token']);
    }

       /**
     * Get user information using the stored access token.
     * example:-
     * getUserInfo()->name;
     * getUserInfo()->email;
     *
     * @return object User information
     */
    public function getUserInfo(){
        $token = $_SESSION['recheck_google_auth_token'];
        $url = "https://www.googleapis.com/oauth2/v3/userinfo?alt=json&access_token=".$token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);    
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }
}