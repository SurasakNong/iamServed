<?php
// required headers
//header("Access-Control-Allow-Origin: http://localhost/iam/");
header("Access-Control-Allow-Origin: http://localhost:8092/iam/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//Load Composer's autoloader
//require($_SERVER['DOCUMENT_ROOT']."/iam/vendor/autoload.php");
require '../api/libs/vendor/autoload.php';  
// files needed to connect to database
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// รับค่าที่ post มา
$data = json_decode(file_get_contents("php://input")); 
// กำหนดค่าให้ตัวแปร
$user->email = $data->email;
 
if(!empty($user->email)){ //ตรวจสอบว่าส่งอีเมล์มารึเปล่า

    if($user->emailExists()){ //อีเมล์นี้มีอยูในระบบหรือไม่
        if($user->sendemail()){  //ส่งอีเมล์
            http_response_code(200);
            echo json_encode(array("message" => "Send link to your Email."));
        } else{
            http_response_code(400);
            echo json_encode(array("message" => "Unable to send Email."));            
        }        
    }else {
        http_response_code(404);
        echo json_encode(array("message" => "Email not found."));
    }
}
 
// กำหนดข้อความเมื่อไม่มีอีเมล์ส่งมา
else{ 
    // set response code
    http_response_code(400); 
    // display message: ไม่มีอีเมล์ส่งมา
    echo json_encode(array("message" => "No Email."));
}
?>

