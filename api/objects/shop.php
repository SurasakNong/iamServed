<?php

class Shop{     
        // database connection and table name
        private $conn;
        private $table_name = "shops";    
        // object properties
        public $s_id;
        public $s_name;
        public $s_desc;
        public $s_mainpic;
        public $s_add;
        public $s_province;
        public $s_amphure;
        public $s_discrict;
        public $s_zip;
        public $s_his;
        public $s_hispic;
        public $s_tel;
        public $s_line;
        public $s_facebook;
        public $u_id;    
        public $ืnamepic;
        public $keypic;    

        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 

    //===== create new user record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    shop_name = :shopname,
                    shop_desc = :shopdesc,
                    shop_main_pic = :shopmainpic,
                    shop_add = :shopadd,
                    shop_province = :shopprovince,
                    shop_amphure = :shopamphure,
                    shop_district = :shopdistrict,
                    shop_zip = :shopzip,
                    shop_his = :shophis,
                    shop_his_pic = :shophispic,
                    shop_tel = :shoptel,
                    shop_line = :shopline,
                    shop_facebook = :shopfacebook,
                    user_id = :userid";
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        // sanitize
        $this->s_name=htmlspecialchars(strip_tags($this->s_name));
        $this->s_desc=htmlspecialchars(strip_tags($this->s_desc));
        $this->s_mainpic=htmlspecialchars(strip_tags($this->s_mainpic));
        $this->s_add=htmlspecialchars(strip_tags($this->s_add));
        $this->s_province=htmlspecialchars(strip_tags($this->s_province));
        $this->s_amphure=htmlspecialchars(strip_tags($this->s_amphure));
        $this->s_discrict=htmlspecialchars(strip_tags($this->s_discrict));
        $this->s_zip=htmlspecialchars(strip_tags($this->s_zip));
        $this->s_his=htmlspecialchars(strip_tags($this->s_his));
        $this->s_hispic=htmlspecialchars(strip_tags($this->s_hispic));
        $this->s_tel=htmlspecialchars(strip_tags($this->s_tel));
        $this->s_line=htmlspecialchars(strip_tags($this->s_line));
        $this->s_facebook=htmlspecialchars(strip_tags($this->s_facebook));
        $this->u_id=htmlspecialchars(strip_tags($this->u_id));    
        // bind the values
        $stmt->bindParam(':shopname', $this->s_name);
        $stmt->bindParam(':shopdesc', $this->s_desc);
        $stmt->bindParam(':shopmainpic', $this->s_mainpic);
        $stmt->bindParam(':shopadd', $this->s_add);
        $stmt->bindParam(':shopprovince', $this->s_province);
        $stmt->bindParam(':shopamphure', $this->s_amphure);
        $stmt->bindParam(':shopdistrict', $this->s_discrict);
        $stmt->bindParam(':shopzip', $this->s_zip);
        $stmt->bindParam(':shophis', $this->s_his);
        $stmt->bindParam(':shophispic', $this->s_hispic);
        $stmt->bindParam(':shoptel', $this->s_tel);
        $stmt->bindParam(':shopline', $this->s_line);
        $stmt->bindParam(':shopfacebook', $this->s_facebook);
        $stmt->bindParam(':userid', $this->u_id);    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }    
        return false;
    } 

    //===== ตรวจสอบว่ผู้ใช้งาน มีการเปิดร้านค้าไว้แล้วหรือยัง
    function shopExits(){    
        $query = "SELECT * 
                FROM " . $this->table_name . "
                WHERE user_id = ?
                LIMIT 0,1";    
        // prepare the query
        $stmt = $this->conn->prepare( $query );    
        // sanitize
        $this->u_id=htmlspecialchars(strip_tags($this->u_id));    
        // bind given email value
        $stmt->bindParam(1, $this->u_id);    
        // execute the query
        $stmt->execute();    
        // get number of rows
        $num = $stmt->rowCount();    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            // assign values to object properties
            $this->s_id = $row['shop_id'];
            $this->s_name = $row['shop_name'];
            $this->s_desc = $row['shop_desc'];
            $this->s_mainpic = $row['shop_main_pic'];
            $this->s_add = $row['shop_add'];
            $this->s_province = $row['shop_province'];
            $this->s_amphure = $row['shop_amphure'];
            $this->s_discrict = $row['shop_district'];
            $this->s_zip = $row['shop_zip'];
            $this->s_his = $row['shop_his'];
            $this->s_hispic = $row['shop_his_pic'];
            $this->s_line = $row['shop_tel'];
            $this->s_facebook = $row['shop_line'];
            $this->u_id = $row['shop_facebook'];
            $this->u_id = $row['user_id'];
            // return true because email exists in the database
            return true;
        }    
        // return false if email does not exist in the database
        return false;
    }

    //===== ตรวจสอบว่า ชื่อร้านค้ามีการใช้งานไปหรือยัง
    function shopNameExit(){
        $sql="SELECT shop_id,shop_name 
        FROM " . $this->table_name. "
        WHERE (shop_name = :shopname)";

        $stmt = $this->conn->prepare($sql);
        $this->s_name = htmlspecialchars(strip_tags($this->s_name));

        $stmt->bindParam(':shopname',$this->s_name);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  // ชื่อร้านค้านี้ถูกใช้ไปแล้ว 
            return true;
        }
        return false;
    }

    //===== ตรวจสอบว่า ชื่อร้านค้าที่ตั้งใหม่มีการใช้งานไปหรือยัง
    function shopUpdateNameExit(){
        $sql="SELECT shop_id,shop_name 
        FROM " . $this->table_name. "
        WHERE (shop_name = :shopname AND shop_id != :shopid)";

        $stmt = $this->conn->prepare($sql);
        $this->s_name = htmlspecialchars(strip_tags($this->s_name));

        $stmt->bindParam(':shopname',$this->s_name);
        $stmt->bindParam(':shopid', $this->s_id); 
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  // ชื่อร้านค้าใหม่นี้ถูกผู้อื่นใช้ไปแล้ว 
            return true;
        }
        return false;
    }


    // update a shop record
    public function update(){
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET 
                    shop_name = :shopname,
                    shop_desc = :shopdesc,
                    shop_add = :shopadd,
                    shop_province = :shopprovince,
                    shop_amphure = :shopamphure,
                    shop_district = :shopdistrict,
                    shop_zip = :shopzip,
                    shop_his = :shophis,
                    shop_tel = :shoptel,
                    shop_line = :shopline,
                    shop_facebook = :shopfacebook
                WHERE shop_id = :shopid";    
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        // sanitize
        $this->s_name=htmlspecialchars(strip_tags($this->s_name));
        $this->s_desc=htmlspecialchars(strip_tags($this->s_desc));
        $this->s_add=htmlspecialchars(strip_tags($this->s_add));
        $this->s_province=htmlspecialchars(strip_tags($this->s_province));
        $this->s_amphure=htmlspecialchars(strip_tags($this->s_amphure));
        $this->s_discrict=htmlspecialchars(strip_tags($this->s_discrict));
        $this->s_zip=htmlspecialchars(strip_tags($this->s_zip));
        $this->s_his=htmlspecialchars(strip_tags($this->s_his));
        $this->s_tel=htmlspecialchars(strip_tags($this->s_tel));
        $this->s_line=htmlspecialchars(strip_tags($this->s_line));
        $this->s_facebook=htmlspecialchars(strip_tags($this->s_facebook));
        // bind the values
        $stmt->bindParam(':shopname', $this->s_name);
        $stmt->bindParam(':shopdesc', $this->s_desc);
        $stmt->bindParam(':shopadd', $this->s_add);
        $stmt->bindParam(':shopprovince', $this->s_province);
        $stmt->bindParam(':shopamphure', $this->s_amphure);
        $stmt->bindParam(':shopdistrict', $this->s_discrict);
        $stmt->bindParam(':shopzip', $this->s_zip);
        $stmt->bindParam(':shophis', $this->s_his);
        $stmt->bindParam(':shoptel', $this->s_tel);
        $stmt->bindParam(':shopline', $this->s_line);
        $stmt->bindParam(':shopfacebook', $this->s_facebook);
        // unique ID of record to be edited
        $stmt->bindParam(':shopid', $this->s_id);    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function update_Pic(){
        $query = "";
        if(!empty($this->namepic)){
            if($this->keypic == "main") {
                $query = "UPDATE shops SET shop_main_pic = :namepic 
                        WHERE shop_id = :shopid ";
            }elseif($this->keypic == "his"){
                $query = "UPDATE shops SET shop_his_pic = :namepic 
                WHERE shop_id = :shopid ";
            }else{
                $query = ""; //อัพเดทรูปข้อมูลรายการสินค้า
            }
            if(!empty($query)){
                // prepare the query
                $stmt = $this->conn->prepare($query);   
                $this->namepic=htmlspecialchars(strip_tags($this->namepic));
                $stmt->bindParam(':namepic', $this->namepic);
                $stmt->bindParam(':shopid', $this->s_id);            
                // execute the query
                if($stmt->execute()){
                    return true;
                }    
            }
        }
        return false;
    }

 
}



