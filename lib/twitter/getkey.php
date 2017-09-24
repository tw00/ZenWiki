<?php

// require
require_once 'twitter.php';

// create instance
$twitter = new Twitter('UnqSTRqit7G1kxCoHJ51g', 'P2Gq9KDa0A9RpEuG9XJSKAdzs7oVimyLhcw8IZuYfY');

// get a request token
#$request_token = $twitter->oAuthRequestToken('http://twitter.com/oauth/request_token');
$request_token = $twitter->oAuthRequestToken();

#print_r($request_token);
$token = $request_token['oauth_token'];
$token_secret = $request_token['oauth_token_secret'];

echo "Token:\t\t", $token, "\n";
echo "Secret Token:\t", $token_secret, "\n";

echo "Now enter the following URL in your Browser and check login.txt:\n";
echo $twitter->getAuthorizeURL($token) . "\n\n";

echo ('Continue?');
fgets(STDIN); 
$response = $twitter->oAuthAccessToken($token, $token_secret );

// authorize
#if(!isset($_GET['oauth_token']))
#print_r(   $twitter->oAuthAuthorize() );


// get tokens
#$response = $twitter->oAuthAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);

// output, you can use the token for setOAuthToken and setOAuthTokenSecret
var_dump($response);

# http://twitter.com/oauth/request_token?oauth_token=bC3lA4Gn0EeNvZsMJdMADvdngCOBZDfRsSYezLg&oauth_verifier=6ylRwg5UnhRLqH0t1ClpIFMY1y6f7ctbOZXyWElyW0w


var_dump( $twitter->accountVerifyCredentials() );
