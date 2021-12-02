<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Bangkok');
 
//=========== variables used for jwt ============================================
$key = "iamserved_123_iloveyou";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid for 1 hour
$issuer = "iamserved";

//============ variables ========================================================
$home = "http://localhost:8092/iam";
$myEmail = "i.am.served.th@gmail.com";
$myEmailPass = "1@mServed";
$myEmailName = "I_am_Served Support";

$pathMainPic = $_SERVER['DOCUMENT_ROOT'].'/iam/img/pic/mainpic/'; //ตำแหน่งเก็บรูปภาพ หน้าร้านค้า
$pathListPic = $_SERVER['DOCUMENT_ROOT'].'/iam/img/pic/listpic/'; //ตำแหน่งเก็บรูปภาพ รายการสินค้า

?>