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
if (isset($_POST['id'])){
    $id = $_POST['id'];
    $sql = "SELECT * FROM shops WHERE shop_id = '$id'"; 

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



