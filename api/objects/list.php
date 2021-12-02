<?php

class Lists{     
        // database connection and table name
        private $conn;
        private $table_name = "list";    
        // object properties
        public $l_id;
        public $l_name;
        public $t_id;
        public $l_desc;
        public $l_pic;
        public $l_st;
        public $l_p1;
        public $l_p2;
        public $l_p3;
        public $l_p4;
        public $s_id;
        public $namepic;

        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 

    //===== create new record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    list_name = :listname,
                    type_id = :typeid,
                    list_desc = :listdesc,
                    list_pic = :listpic,
                    list_st = :listst,
                    list_p1 = :listp1,
                    list_p2 = :listp2,
                    list_p3 = :listp3,
                    list_p4 = :listp4
                    ";
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        // sanitize
        $this->l_name=htmlspecialchars(strip_tags($this->l_name));
        $this->t_id=htmlspecialchars(strip_tags($this->t_id));
        $this->l_desc=htmlspecialchars(strip_tags($this->l_desc));
        $this->l_pic=htmlspecialchars(strip_tags($this->l_pic));
        $this->l_st=htmlspecialchars(strip_tags($this->l_st));
        $this->l_p1=htmlspecialchars(strip_tags($this->l_p1));
        $this->l_p2=htmlspecialchars(strip_tags($this->l_p2));
        $this->l_p3=htmlspecialchars(strip_tags($this->l_p3));
        $this->l_p4=htmlspecialchars(strip_tags($this->l_p4));
   
        // bind the values
        $stmt->bindParam(':listname', $this->l_name);
        $stmt->bindParam(':typeid', $this->t_id);
        $stmt->bindParam(':listdesc', $this->l_desc);
        $stmt->bindParam(':listpic', $this->l_pic);
        $stmt->bindParam(':listst', $this->l_st);
        $stmt->bindParam(':listp1', $this->l_p1);
        $stmt->bindParam(':listp2', $this->l_p2);
        $stmt->bindParam(':listp3', $this->l_p3);      
        $stmt->bindParam(':listp4', $this->l_p4);   
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }    
        return false;
    } 


    // update a record
    public function update(){
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . " 
                SET list_name = :listname,
                type_id = :typeid,
                list_desc = :listdesc,
                list_st = :listst,
                list_p1 = :listp1,
                list_p2 = :listp2,
                list_p3 = :listp3,
                list_p4 = :listp4                                       
                 WHERE list_id = :listid";    
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        $this->l_name=htmlspecialchars(strip_tags($this->l_name));
        $this->t_id=htmlspecialchars(strip_tags($this->t_id));
        $this->l_desc=htmlspecialchars(strip_tags($this->l_desc));
        $this->l_st=htmlspecialchars(strip_tags($this->l_st));
        $this->l_p1=htmlspecialchars(strip_tags($this->l_p1));
        $this->l_p2=htmlspecialchars(strip_tags($this->l_p2));
        $this->l_p3=htmlspecialchars(strip_tags($this->l_p3));
        $this->l_p4=htmlspecialchars(strip_tags($this->l_p4));
        $this->l_id=htmlspecialchars(strip_tags($this->l_id));
   
        // bind the values
        $stmt->bindParam(':listname', $this->l_name);
        $stmt->bindParam(':typeid', $this->t_id);
        $stmt->bindParam(':listdesc', $this->l_desc);
        $stmt->bindParam(':listst', $this->l_st);
        $stmt->bindParam(':listp1', $this->l_p1);
        $stmt->bindParam(':listp2', $this->l_p2);
        $stmt->bindParam(':listp3', $this->l_p3);    
        $stmt->bindParam(':listp4', $this->l_p4);   
        $stmt->bindParam(':listid', $this->l_id);       
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

     // delete a record
     public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE list_id = :listid";
        $stmt = $this->conn->prepare($query);    
        $this->l_id = htmlspecialchars(strip_tags($this->l_id));
        $stmt->bindParam(':listid', $this->l_id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function listdetail(){ //ตรวจสอบว่ามีรายการตามรหัสนี้หรือไม่
        $query = "SELECT * FROM " . $this->table_name . " WHERE list_id = :listid";
        $stmt = $this->conn->prepare($query);
        $this->l_id = htmlspecialchars(strip_tags($this->l_id));
        $stmt->bindParam(':listid', $this->l_id);
        $stmt->execute();

        $num = $stmt->rowCount();
        if($num > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            // assign values to object properties             
            $this->l_pic = $row['list_pic'];                 
            return true;
        }
        return false;
    }

    public function listExits(){ //ตรวจสอบว่ามีชื่อรายการ ที่ร้านค้านี้ใช้แล้วหรือยัง
        $query = "SELECT list_id, list_name, shop_id FROM ". $this->table_name . " 
        INNER JOIN type ON list.type_id = type.type_id 
        WHERE (type.shop_id = :shopid AND list_name = :listname) LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->l_name=htmlspecialchars(strip_tags($this->l_name));
        $this->s_id=htmlspecialchars(strip_tags($this->s_id));
        $stmt->bindParam(':shopid', $this->s_id);
        $stmt->bindParam(':listname', $this->l_name);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            // assign values to object properties
            $this->l_id = $row['list_id'];
            $this->l_name = $row['list_name'];            
            return true;
        }
        return false;
    }

    public function listnameExits(){ //ตรวจสอบว่ามีชื่อรายการ ที่ร้านค้าอื่นใช้งานหรือยัง
        $query = "SELECT list_id, list_name, shop_id FROM ". $this->table_name . " 
        INNER JOIN type ON list.type_id = type.type_id 
        WHERE ((type.shop_id = :shopid AND list_name = :listname) AND list_id != :listid ) LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->l_name=htmlspecialchars(strip_tags($this->l_name));
        $this->s_id=htmlspecialchars(strip_tags($this->s_id));
        $this->l_id=htmlspecialchars(strip_tags($this->l_id));
        $stmt->bindParam(':shopid', $this->s_id);
        $stmt->bindParam(':listname', $this->l_name);
        $stmt->bindParam(':listid', $this->l_id);  
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num > 0){
            return true;
        }
        return false;
    }

    public function update_Pic(){
        $query = "";
        if(!empty($this->l_pic)){
            $query = "UPDATE list SET list_pic = :listpic WHERE list_id = :listid ";            
            // prepare the query
            $stmt = $this->conn->prepare($query);   
            $this->l_pic=htmlspecialchars(strip_tags($this->l_pic));
            $stmt->bindParam(':listpic', $this->l_pic);
            $stmt->bindParam(':listid', $this->l_id);            
            // execute the query
            if($stmt->execute()){
                return true;
            }                
        }
        return false;
    }


}



