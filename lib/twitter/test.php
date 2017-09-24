<?php

// require
require_once 'twitter.php';

// create instance
$twitter = new Twitter('UnqSTRqit7G1kxCoHJ51g', 'P2Gq9KDa0A9RpEuG9XJSKAdzs7oVimyLhcw8IZuYfY');

#print_r($request_token);
$token = 'aKCLjLwrY4kAHm64hbzj5zf1VJ2g6VWkxYQxsU0BUBQ';

$response = $twitter->oAuthAccessToken($token, '');
#print_r(   $twitter->oAuthAuthorize() );
var_dump($response);


