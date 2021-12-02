<?php
$file = $_GET['file'];

header('Content-type: image/jpeg');

list($width,$height) = getimagesize($file);
$thumb = imagecreatetruecolor("100","80");
$source = imagecreatefromjpeg($file);
imagecopyresized($thumb,$source,0,0,0,0,"100","80",$width,$height);
imagejpeg($thumb,"",80);

imagedestroy($source);
imagedestroy($thumb);

?>