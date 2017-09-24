<?php

/* HACK !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
/* HACK !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
/* HACK !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
/* AND UNSECURE */

header('Content-type: image/jpeg');

$w = 80;
$h = 80;

$img = $_GET['path'];

$x = @getimagesize($img);
$sw = $x[0];
$sh = $x[1];

$im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
$im = @ImageCreateFromPNG ($img) or // or PNG Image
$im = @ImageCreateFromGIF ($img) or // or GIF Image
$im = false; // If image is not JPEG, PNG, or GIF

if (!$im) {
    readfile ($img);
} else {
    $thumb = @ImageCreateTrueColor ($w, $h);
    @ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
    @ImageJPEG ($thumb);
}
