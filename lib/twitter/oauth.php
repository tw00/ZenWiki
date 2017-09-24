<?php

file_put_contents("login.txt", print_r( $_REQUEST, true ), FILE_APPEND | LOCK_EX );

echo "<pre>";
var_dump( $_REQUEST );
var_dump( $_GET );
var_dump( $_POST );

#header('Location: http://wiki.wavefab.com');
