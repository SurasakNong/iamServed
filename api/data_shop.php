<?php
include_once 'config/core.php';
//===== required headers
//header("Access-Control-Allow-Origin: http://localhost/iam/");
header("Access-Control-Allow-Origin: ${home}");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//===== files needed to connect to database
include_once 'config/database.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
$sql = "";
$id = isset($_POST['id'])?$_POST['id']:"";
$mode = isset($_POST['mode'])?$_POST['mode']:"";
if (!empty($id)){    
    if($mode == 'shop'){
        $sql = "SELECT shops.*, concat(shop_add,' ตำบล',districts.name_th,' อำเภอ',amphures.name_th,' จังหวัด',provinces.name_th,' ',shop_zip) as s_address FROM (((shops JOIN provinces ON shop_province = provinces.id) JOIN amphures ON shop_amphure = amphures.id) JOIN districts ON shop_district = districts.id) WHERE shop_id = '$id'";
    }elseif($mode == 'user'){
        $sql = "SELECT shops.*, concat(shop_add,' ตำบล',districts.name_th,' อำเภอ',amphures.name_th,' จังหวัด',provinces.name_th,' ',shop_zip) as s_address FROM (((shops JOIN provinces ON shop_province = provinces.id) JOIN amphures ON shop_amphure = amphures.id) JOIN districts ON shop_district = districts.id) WHERE user_id = '$id'";
    }else{ $sql = "";    }     

    if($sql != ""){
        // prepare the query
        $stmt = $db->prepare( $sql ); 
        $stmt->execute();
        $resultArray = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($resultArray,$row);
        }
        $database = null;
        echo json_encode($resultArray);
    }
    
}

?>



