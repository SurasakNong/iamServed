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
include_once 'objects/type.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

$type = new Type($db);
//$acc = isset($_POST['acc'])?$_POST['acc']:"";
$data = json_decode(file_get_contents("php://input"));
$acc=isset($data->acc) ? $data->acc : "";
$jwt=isset($data->jwt) ? $data->jwt : "";
if($jwt){ 
    // if decode succeed, show user details
    try { 
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // decode jwt
        if(!empty($acc) && $acc == "add"){ //ทำการเพิ่มข้อมูล
            $type->t_name = $data->typename;
            $type->t_st = $data->typest;
            $type->s_id = $data->shopid;    
            if( !empty($type->t_name) && !empty($type->s_id)){
                if($type->typeExits()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Type Exit."));
                }else {
                    $type->create();
                    http_response_code(200);
                    echo json_encode(array("message" => "Type was created."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to create Type."));
            } 
        }else if(!empty($acc) && $acc == "up"){ //ปรับปรุงแก้ไขข้อมูล
            $type->t_id = $data->typeid;
            $type->t_name = $data->typename;
            $type->t_st = $data->typest;
            $type->s_id = $data->shopid;   
            if( !empty($type->t_name) && !empty($type->s_id)){
                if($type->typenameExits()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Type Exit."));
                }else {
                    $type->update();
                    http_response_code(200);
                    echo json_encode(array("message" => "Type was update."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to update Type."));
            }

        }else if(!empty($acc) && $acc == "del"){ //ลบข้อมูล           
            $type->t_id = $data->typeid;
           if(!empty($type->t_id) && $type->delete()){                               
                http_response_code(200);
                echo json_encode(array("message" => "Type was delete."));                
           }else{
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to delete Type."));
           }   

        }else{
            http_response_code(400); 
            echo json_encode(array("message" => "Unable to access Type."));
        }
    }
    
    catch (Exception $e){    // if decode fails, it means jwt is invalid
        http_response_code(401);    
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}else{  // show error message if jwt is empty
    http_response_code(401); 
    echo json_encode(array("message" => "Access denied."));
}







?>

