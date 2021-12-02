<?php
include_once 'config/core.php';
// required headers
header("Access-Control-Allow-Origin: ${home}");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/list.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

$list = new Lists($db);
$data = json_decode(file_get_contents("php://input"));
$acc=isset($data->acc) ? $data->acc : "";
$jwt=isset($data->jwt) ? $data->jwt : "";
if($jwt){ 
    // if decode succeed, show user details
    try { 
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // decode jwt
        if(!empty($acc) && $acc == "add"){ //ทำการเพิ่มข้อมูล
            $list->l_name = $data->listname;
            $list->t_id = $data->typelist;  
            $list->l_desc = $data->listdesc;
            $list->l_pic = "image-not-available.png";
            $list->l_st = $data->listst;
            $list->l_p1 = $data->listp1;
            $list->l_p2 = $data->listp2;
            $list->l_p3 = $data->listp3;
            $list->l_p4 = $data->listp4;  
            $list->s_id = $data->shopid;
            if( !empty($list->l_name)){
                if($list->listExits()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "List Exit."));
                }else {
                    $list->create();
                    $list->listExits();
                    http_response_code(200);
                    echo json_encode(array("message" => "List was created.", "list_id" => $list->l_id));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to create List."));
            }
        }else if(!empty($acc) && $acc == "up"){ //ปรับปรุงแก้ไขข้อมูล
            $list->l_id = $data->listid;
            $list->s_id = $data->shopid;            
            $list->l_name = $data->listname;
            $list->t_id = $data->typelist;  
            $list->l_desc = $data->listdesc;
            $list->l_st = $data->listst;
            $list->l_p1 = $data->listp1;
            $list->l_p2 = $data->listp2;
            $list->l_p3 = $data->listp3;
            $list->l_p4 = $data->listp4;               
            if(!empty($list->l_id) && !empty($list->s_id)){
                if($list->listnameExits()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "List Exit."));
                }else {
                    $list->update();
                    http_response_code(200);
                    echo json_encode(array("message" => "List was update.", "list_id" => $list->l_id));
                }                
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to update List."));
            }            

        }else if(!empty($acc) && $acc == "del"){ //ลบข้อมูล           
            $list->l_id = $data->listid;
            if($list->listdetail()){
                $list->delete();  //ลบข้อมูลรายการสินค้า              
                $filedel = glob($pathListPic.substr($list->l_pic,0,strlen($list->l_pic)-3)."*");
                foreach ($filedel as $file) { //ลบไฟล์รูปภาพรายการสินค้า 
                    unlink($file);
                }             
                http_response_code(200);
                echo json_encode(array("message" => "List was delete."));
            }else {
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to delete List."));
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

