<?php

class Type{     
        // database connection and table name
        private $conn;
        private $table_name = "type";    
        // object properties
        public $t_id;
        public $t_name;
        public $t_st;
        public $s_id;
            

        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 

    //===== create new record
    function create(){    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    type_name = :typename,
                    type_st = :typest,
                    shop_id = :shopid";
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        // sanitize
        $this->t_name=htmlspecialchars(strip_tags($this->t_name));
        $this->s_id=htmlspecialchars(strip_tags($this->s_id));    
        // bind the values
        $stmt->bindParam(':typename', $this->t_name);
        $stmt->bindParam(':typest', $this->t_st);
        $stmt->bindParam(':shopid', $this->s_id);        
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
                SET type_name = :typename, 
                type_st = :typest                                          
                WHERE type_id = :typeid";    
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        $this->t_name = htmlspecialchars(strip_tags($this->t_name));
        $this->t_id = htmlspecialchars(strip_tags($this->t_id));

        $stmt->bindParam(':typename', $this->t_name);
        $stmt->bindParam(':typest', $this->t_st);
        $stmt->bindParam(':typeid', $this->t_id);    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

     // delete a record
     public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE type_id = :typeid";
        $stmt = $this->conn->prepare($query);    
        $this->t_id = htmlspecialchars(strip_tags($this->t_id));
        $stmt->bindParam(':typeid', $this->t_id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // deta exits
    public function typeExits(){
        $query = "SELECT type_name, shop_id
                FROM " . $this->table_name . "
                WHERE (shop_id = :shopid AND type_name = :typename)";

        $stmt = $this->conn->prepare($query);
        $this->t_name=htmlspecialchars(strip_tags($this->t_name));
        $this->s_id=htmlspecialchars(strip_tags($this->s_id));
        $stmt->bindParam(':shopid', $this->s_id);
        $stmt->bindParam(':typename', $this->t_name);
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0){
            return true;
        }
        return false;
    }

    public function typenameExits(){
        $query = "SELECT type_name, shop_id
                FROM " . $this->table_name . "
                WHERE ((shop_id = :shopid AND type_name = :typename) AND type_id != :typeid)";

        $stmt = $this->conn->prepare($query);
        $this->t_name=htmlspecialchars(strip_tags($this->t_name));
        $this->s_id=htmlspecialchars(strip_tags($this->s_id));
        $stmt->bindParam(':shopid', $this->s_id);
        $stmt->bindParam(':typename', $this->t_name);
        $stmt->bindParam(':typeid', $this->t_id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num > 0){
            return true;
        }
        return false;
    }


}



