<?php


$GLOBALS = array(
    '_SERVER' => array(
        'REDIRECT_URI' => null,
        'REMOTE_ADDR' => "",
        #'REQUEST_URI' =>  "http://wiki.wavefab.com/Kundenstamm/Markler"
        'REQUEST_URI' =>  "/Kundenstamm/Markler"
    )
);
print_r( $GLOBALS );
/* 
$GLOBALS['HTTP_ENV_VARS']['REDIRECT_URI'] = null;
$GLOBALS['HTTP_ENV_VARS']['REMOTE_ADDR'] = "";
$GLOBALS['HTTP_ENV_VARS']['REQUEST_URI'] = "http://wiki.wavefab.com/Kundenstamm/Markler"
*/
Dispatcher::run();
