<?php

function enCodeEmail($inId){
    $arrCode = [];
    $nid = strlen($inId);
    $nid = ($nid>9)?$nid:'0'.$nid;

    date_default_timezone_set("Asia/Bangkok");

    $Tstamp = time();
    $dateStamp = date("d F Y H:i:s", $Tstamp);
    // "+15 minutes","+1 day"
    $endTime = strtotime("+1 day", strtotime($dateStamp)); 
    //$date2 = date("d F Y H:i:s", $endTime);
    
    $sub_code = substr($endTime,strlen($endTime)-6);
    $sumCode = 0;
    for($i=0;$i<6;$i++){ $sumCode = $sumCode + (int)substr($sub_code,$i,1); }
    $sumCode = ($sumCode<10)?'0'.$sumCode:$sumCode;     
    $ncode=strlen($endTime);
    $ncode = ($ncode<10)?'0'.$ncode:$ncode;
    array_push($arrCode,$sumCode);
    array_push($arrCode,$endTime);
    array_push($arrCode,$ncode);
    array_push($arrCode,$inId);
    array_push($arrCode,$nid);

    //return $arrCode;
    return implode($arrCode);
}

function deCodeEmail($autoCode){
    $result = [];
    if(!empty($autoCode) && is_numeric($autoCode)){
        $n_ac = strlen($autoCode);
        $n1 = (int)substr($autoCode,$n_ac-2,2);
        $n2 = (int)substr($autoCode,$n_ac-(2+$n1+2),2);
        $endTime = (int)substr($autoCode,$n_ac-(2+$n1+2+$n2),$n2);
        $id=(int)substr($autoCode,$n_ac-2-$n1,$n1);

        $result[0] = 1;
        $result[1] = $endTime;
        $result[2] = $id;
    }else{
        $result[0] = 0;
        $result[1] = 'Invalid value';
    }

    return $result;
}

function resizePic(){
    $images = "img/pic/mainpic/000000039main.jpg";
	$new_images = "uploads/mypic.jpg";
	$width=200; //*** Fix Width & Heigh (Autu caculate) ***//
	$size=GetimageSize($images);
	$height=round($width*$size[1]/$size[0]);
	$images_orig = ImageCreateFromJPEG($images);
	$photoX = ImagesX($images_orig);
	$photoY = ImagesY($images_orig);
	$images_fin = ImageCreateTrueColor($width, $height);
	ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
	ImageJPEG($images_fin,$new_images);
	ImageDestroy($images_orig);
	ImageDestroy($images_fin);
}

function image_resize($file_name, $width, $height, $crop=FALSE) {
    list($wid, $ht) = getimagesize($file_name);
    $r = $wid / $ht;
    if ($crop) {
       if ($wid > $ht) {
          $wid = ceil($wid-($width*abs($r-$width/$height)));
       } else {
          $ht = ceil($ht-($ht*abs($r-$wid/$ht)));
       }
       $new_width = $width;
       $new_height = $height;
    } else {
       if ($width/$height > $r) {
          $new_width = $height*$r;
          $new_height = $height;
       } else {
          $new_height = $width/$r;
          $new_width = $width;
       }
    }
    $source = imagecreatefromjpeg($file_name);
    $dst = imagecreatetruecolor($new_width, $new_height);
    ImageCopyResampled($dst, $source, 0, 0, 0, 0, $new_width, $new_height, $wid, $ht);
    return $dst;
 }


 function imageResize( $ext, $ori_file, $new_file )
    {   
        $max_imageSize = 400;
        $ori_size = getimagesize($ori_file);
        $ori_w = $ori_size[0];
        $ori_h = $ori_size[1];
         
        if($ori_w > $ori_h) {
            $new_w = $max_imageSize;
            $new_h = round(($new_w/$ori_w) * $ori_h);
        }
        else
        {
            $new_h = $max_imageSize;
            $new_w = round(($new_h/$ori_h) * $ori_w);
        }
     
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
        imagecopyresized($new_img, $ori_img, 0, 0, 0, 0, $new_w, $new_h, $ori_w, $ori_h);
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

function hello(){
    echo 'Hello  <br>';
    resizePic();
    
}

if (isset($_GET['hello'])) {
    hello();
  }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Document</title>
    <style>
        h1{
            border-radius: 60px 0 60px 0;
            background: linear-gradient(90deg,#FA420D 50%,#FABC0D 100%);
            padding: 20px;
            margin-bottom:10px;
            font-size:30px;
            color:white;
            text-align: center;
        }
    </style>
</head>

<body>
<h1 >I am Served</h1>

<a href='code_test.php?hello=true'>Run PHP Function</a>
<!--<img src="img/pic/mainpic/000000039main.jpg"> -->
<?php
echo '<br>';
echo enCodeEmail('39');
echo '<br>';
//http://192.168.50.14:8092/iam/index.html?mode=221623126391103902&key=123456789
$a=[];
$a = deCodeEmail(enCodeEmail('39'));
echo '<br>';
echo $a[0].'=>'.$a[1];
echo '<br>';
echo 'id = '.$a[2];
echo '<br>';
$l = ['a','b','c','d','e','f','0','1','2','3','4','5'];
echo $l[rand(0,11)].rand(100, 9999).$l[rand(0,11)].$l[rand(0,11)].rand(100, 9999).$l[rand(0,11)];
?>


<div id="table" style="background-color: linear-gradient(90deg,#FA420D 50%,#FABC0D 100%);"></div>

<div style='background: linear-gradient(90deg,#FA420D 50%,#FABC0D 100%);
color: #FABC0D; 
padding:20px; 
text-align:center; 
border-radius: 0 60px 0 60px;'>
                                            2022 © I am Served Thailand
                                    </div>
                                    
<script src="./js/jquery.3.6.0.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">  
    $(function(){  
    
        var _get = function(val){
            var result = null; // กำหนดค่าเริ่มต้นผลลัพธ์
                tmp = []; // กำหนดตัวแปรเก็บค่า เป็น array
                // เก็บค่า url โดยตัด ? อันแรกออก แล้วแยกโดยตัวแบ่ง &
            var items = location.search.substr(1).split("&"); 
            for(var index = 0; index < items.length; index++) { // วนลูป
                tmp = items[index].split("="); // แยกระหว่างชื่อตัวแปร และค่าของตัวแปร
                // ถ้าค่าที่ส่งมาตรวจสอบชื่อตัวแปรตรง ให้เก็บค่าผลัพธ์เป็นค่าของตัวแปรนั้นๆ
                if(tmp[0] === val) result = decodeURIComponent(tmp[1]);
            }
            return result;  // คืนค่าของตัวแปรต้องการ ถ้าไม่มีจะเป็น null
        }

        console.log(_get('a')); // ได้ค่า null
        console.log(_get('b'));  // ได้ค่าของตัวแปร  a
        console.log(_get('c'));  // ได้ค่าของตัวปร b
        if(_get('a') && _get('b')){ // ใช้ตรวจสอบ ถ้ามีการส่งค่าของตัวแปร a และ b
            console.log("OK");  
        }
        if(_get('a') || _get('b')){ // ใช้ตรวจสอบ ถ้ามีการส่งค่าของตัวแปร a หรือ b
            console.log("OK");  
        }
            
    });

    $(document).ready(function(){
        var html =`
        <table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Larry the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
        `;
        $('#table').html(html);


    });
</script>
</body>

</html>