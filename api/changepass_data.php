<?php
// required headers
//header("Access-Control-Allow-Origin: http://localhost/iam/");
header("Access-Control-Allow-Origin: http://localhost:8092/iam/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//require($_SERVER['DOCUMENT_ROOT']."/iam/vendor/autoload.php");

// files needed to connect to database
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
 
// รับค่าที่ทำการ post มา
$data = json_decode(file_get_contents("php://input"));
 
// กำหนดค่าให้ตัวแปร
$user->id = $data->id;
$user->email = $data->email;
$user->password = $data->password;

if(!empty($user->email)){ // ตรวจสอบอีเมล์
    if($user->email_id_Exit()){ // ตรวจสอบอีเมล์และไอดีผู้ใช้มีอยู่หรือไม่
        if($user->changePass()){ // เปลี่ยนรหัสผ่าน
            http_response_code(200);
            echo json_encode(array("message" => "Change password already."));
        } else{
            http_response_code(400);
            echo json_encode(array("message" => "Unable to change password."));            
        }        
    }else {
        http_response_code(404);
        echo json_encode(array("message" => "Id and Email not found."));
    }
}
 
else{ 
    // set response code
    http_response_code(400); 
    // display message: ไม่ระบุอีเมล์มา
    echo json_encode(array("message" => "No Email."));
}
?>

