<?php
include_once 'config/core.php';
//===== required headers
header("Access-Control-Allow-Origin: ${home}");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//===== files needed to connect to database
include_once 'config/database.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
// get database connection
$database = new Database();
$db = $database->getConnection();
$sql = "";
$jwt = isset($_POST['jwt'])?$_POST['jwt']:"";
if(!empty($jwt)){
    try{
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // ถอดรหัส jwt
        if (isset($_POST['id'])){
            $id = $_POST['id'];
            $perpage = isset($_POST['perpage'])?(int)$_POST['perpage']:10;
            $page = isset($_POST['page'])?(int)$_POST['page']:1;
            $search = isset($_POST['search'])?$_POST['search']:"";
            $search = htmlspecialchars(strip_tags($search));
            $id = htmlspecialchars(strip_tags($id)); 
            $rowStart = ($page-1)*$perpage;
            $sql = "SELECT * FROM type WHERE (shop_id = :id AND CONCAT(type_name,' ',IF(type_st = 0 ,'ใช้ปกติ','ไม่ใช้งาน')) LIKE '%".$search."%') ORDER BY type_name LIMIT $rowStart , $perpage";
                $stmt = $db->prepare( $sql );  
                $stmt->bindParam(':id', $id);     
                $stmt->execute();
                $num = $stmt->rowCount();   
                $resultArray = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($resultArray,$row);
                }        
                    $sql2 = "SELECT * FROM type WHERE (shop_id = :id AND CONCAT(type_name,' ',IF(type_st = 0 ,'ใช้ปกติ','ไม่ใช้งาน')) LIKE '%".$search."%')";
                    $stmt2 = $db->prepare( $sql2 );              
                    $stmt2->bindParam(':id', $id); 
                    $stmt2->execute();
                    $numall = $stmt2->rowCount();  
                $allpage = ceil($numall/$perpage);
                $database = null;
                echo json_encode(
                    array(
                        "data" => $resultArray,
                        "page_all" => $allpage
                    )
                );    
        }
    }
    catch (Exception $e){    //ถอดรหัส JWT ไม่ถูกต้อง
        http_response_code(401);    
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}else{ //ไม่พบ JWT
    http_response_code(401); 
    echo json_encode(array("message" => "Access denied."));
}

?>



