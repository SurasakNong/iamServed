<?php
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/shop.php';
include_once 'objects/list.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// get database connection
$database = new Database();
$db = $database->getConnection(); 
// instantiate product object
$shop = new Shop($db);
$list = new Lists($db);
$key = isset($_POST['key'])?$_POST['key']:"";

    if (isset($_FILES['files'])) {
        $errors = [];  
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];               
        $file_name = $_FILES['files']['name'];
        $file_tmp = $_FILES['files']['tmp_name'];
        $file_type = $_FILES['files']['type'];
        $file_size = $_FILES['files']['size'];
        $file_ext = strtolower(end(explode('.', $_FILES['files']['name'])));
        $exp = explode('.' , $filename);
        //$file_name_old = substr($filename , 0 , -(strlen($exp[count($exp)-1])+1));
        if (!in_array($file_ext, $extensions)) {
            $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
        }
        if ($file_size > 2097152) {
            $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
        }

        if(($key === "main" || $key === "his") && empty($errors)) {
            $path = $pathMainPic; //ตำแหน่งเก็บรูปภาพ หน้าร้านค้า
            $shop->s_id = (int)$_POST['id_shop'];
            $new_name = $_POST['id_shop'].$key;            
            $file = $path . $new_name.'.'.$file_ext;
            //move_uploaded_file($file_tmp, $file);  
            imageResize( $file_ext, $file_tmp, $file );
            $shop->keypic = $key;
            $shop->namepic = $new_name.'.'.$file_ext;                
            $shop->update_Pic();

        }else if($key === "list" && empty($errors)) {
            $path = $pathListPic; //ตำแหน่งเก็บรูปภาพ รายการสินค้า
            $list->l_id = (int)$_POST['id_list'];
            $new_name = $_POST['id_list'].$key;       
            $file = $path . $new_name.'.'.$file_ext;
            imageResize( $file_ext, $file_tmp, $file ); 
            $list->l_pic = $new_name.'.'.$file_ext;
            $list->update_Pic();

        }else{
            $errors[] = 'Key access not found!';

        }
                   
    }else{
        $errors[] = 'File not found!';
    }
    if (!empty($errors)) {print_r($errors);}

}

function imageResize( $ext, $ori_file, $new_file )
    {
        list($width, $height) = getimagesize($ori_file);
        $new_h = ($height>550)?550:$height;  //กำหนดความสูงของรูปภาพให้ไม่เกิน 550px
        $new_w = round(($new_h/$height) * $width);
        
        if ($ext == "jpg" or $ext == "jpeg") {
            $ori_img = imagecreatefromjpeg($ori_file);
        } else
        if ($ext == "png") {
            $ori_img = imagecreatefrompng($ori_file);
        } else
        if ($ext == "gif") {
            $ori_img = imagecreatefromgif($ori_file);
        } 
 
        $new_img = imagecreatetruecolor($new_w, $new_h);
        imagecopyresized($new_img, $ori_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        if ($ext == "jpg" or $ext == "jpeg") {
            imagejpeg($new_img, $new_file); 
        } else
        if ($ext == "png") {
            imagepng($new_img, $new_file); 
        } else
        if ($ext == "gif") {
            imagegif($new_img, $new_file); 
        }
         
        imagedestroy($ori_img);
        imagedestroy($new_img);
    }



?>