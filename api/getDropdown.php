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
if (isset($_POST['fn'])){
    $id = $_POST['id'];
    if ($_POST['fn'] == 'provinces') {
        $sql = "SELECT id, name_th FROM provinces GROUP BY name_th";   
    }elseif($_POST['fn'] == 'provinces_sel'){
        $sql = "SELECT id, name_th FROM amphures WHERE province_id = '$id' GROUP BY name_th"; 
    }elseif($_POST['fn'] == 'amphures'){
        $sql = "SELECT id, name_th FROM amphures GROUP BY name_th"; 
    }elseif($_POST['fn'] == 'amphures_sel'){
        $sql = "SELECT id, name_th FROM districts WHERE amphure_id = '$id' GROUP BY name_th"; 
    }elseif($_POST['fn'] == 'districts'){
        $sql = "SELECT id, name_th FROM districts GROUP BY name_th"; 
    }elseif($_POST['fn'] == 'districts_sel'){        
        $sql = "SELECT zip_code FROM districts WHERE id = '$id'"; 
    }elseif($_POST['fn'] == 'geo_sel'){
        $sql = "SELECT id, name_th FROM provinces WHERE geography_id = '$id' GROUP BY name_th"; 
    }elseif($_POST['fn'] == 'geo'){
        $sql = "SELECT id, name FROM geographies "; 
    }elseif($_POST['fn'] == 'type'){
        $sql = "SELECT type_id, type_name FROM type WHERE shop_id = '$id' GROUP BY type_name"; 
    }elseif($_POST['fn'] == 'typeadd'){
        $sql = "SELECT type_id, type_name FROM type WHERE (shop_id = '$id' AND type_st = '0') GROUP BY type_name"; 
    }else{
        $sql = "";
    }
    
    
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



