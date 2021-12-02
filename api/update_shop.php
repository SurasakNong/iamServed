<?php
// required headers
//header("Access-Control-Allow-Origin: http://localhost/iam/");
header("Access-Control-Allow-Origin: http://localhost:8092/iam/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// database connection will be here
// files needed to connect to database
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/shop.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$shop = new Shop($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
$jwt=isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if($jwt){ 
    // if decode succeed, show user details
    try { 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256')); 
        // set product property values        
        $shop->s_id = $data->shopid;
        $shop->s_name = $data->shopname;
        $shop->s_desc = $data->shopdesc;
        //$shop->s_mainpic = "image-not-available.png";
        $shop->s_add = $data->shopadd;
        $shop->s_province = $data->shopprovince;
        $shop->s_amphure = $data->shopamphures;
        $shop->s_discrict = $data->shopdistricts;
        $shop->s_zip = $data->shopzip;
        $shop->s_his = $data->shophis;
        //$shop->s_hispic = "image-not-available.png"; 
        $shop->s_tel = $data->shoptel;
        $shop->s_line = $data->lineid;
        $shop->s_facebook = $data->facebookid;
        
        if($shop->shopUpdateNameExit()){ //ตรวจสอบว่าชื่อร้านค้ามีค้านอื่นใช้ซ้ำหรือไม่ ถ้ามีแจ้งเตือน
            http_response_code(401);        // set response code             
            echo json_encode(array("message" => "Name already exists."));
        }else{  
            $shop->update(); 
            $shop->shopExits();
            http_response_code(200);
            echo json_encode(array("message" => "Shop updated.","id_shop" => $shop->s_id));
        }
    }
 
    // if decode fails, it means jwt is invalid
    catch (Exception $e){    
        // set response code
        http_response_code(401);    
        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}
 
// show error message if jwt is empty
else{ 
    // set response code
    http_response_code(401); 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}


?>

