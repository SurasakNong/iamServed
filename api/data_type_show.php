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
$id = isset($_POST['id'])?$_POST['id']:"";
if (!empty($id)){    
    $sql = "SELECT type.type_id, type_name,COUNT(list_id) AS qty FROM type INNER JOIN list ON type.type_id = list.type_id WHERE (type_st ='0' AND list_st = '0' AND shop_id = '$id') GROUP BY type_id ORDER BY type_name";
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

?>



