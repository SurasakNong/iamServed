<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User{ 
    
        // database connection and table name
        private $conn;
        private $table_name = "users";
    
        // object properties
        public $id;
        public $firstname;
        public $lastname;
        public $tel;
        public $email;
        public $password;
        public $type;
    
        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 

    //===== create new user record
    function create(){
    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
    
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
    } 

    function userIdExists(){    
        // query to check if User Id exists
        $query = "SELECT id, firstname, lastname, type, email, password
                FROM " . $this->table_name . "
                WHERE id = ?
                LIMIT 0,1";    
        // prepare the query
        $stmt = $this->conn->prepare( $query );    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->id));    
        // bind given email value
        $stmt->bindParam(1, $this->id);    
        // execute the query
        $stmt->execute();    
        // get number of rows
        $num = $stmt->rowCount();    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->password = $row['password'];     
            $this->type = $row['type'];    
            // return true because email exists in the database
            return true;
        }    
        // return false if email does not exist in the database
        return false;
    }

    //===== check if given email exist in the database
    function emailExists(){    
        // query to check if email exists
        $query = "SELECT id, firstname, lastname, type, password
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";    
        // prepare the query
        $stmt = $this->conn->prepare( $query );    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));    
        // bind given email value
        $stmt->bindParam(1, $this->email);    
        // execute the query
        $stmt->execute();    
        // get number of rows
        $num = $stmt->rowCount();    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];     
            $this->type = $row['type'];    
            // return true because email exists in the database
            return true;
        }    
        // return false if email does not exist in the database
        return false;
    }

    //===== ตรวจสอบว่า อีแมล์นี้ซืำกับของผู้อื่นหรือไม่ (นอกจากจะเป็นของผู้ใช้เอง)
    function newmailExit(){
        $sql="SELECT id, firstname, lastname, password 
        FROM " . $this->table_name. "
        WHERE (email = :email AND id != :id)";

        $stmt = $this->conn->prepare($sql);
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':email',$this->email);
        $stmt->bindParam(':id',$this->id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  // อีเมล์นี้มีผู้อื่นใช้แล้ว 
            return true;
        }
        return false;
    }

    //===== ตรวจสอบว่า อีเมล์และไอดีผู้ใช้นี้ มีอยู่หรือไม่
    function email_id_Exit(){
        $sql="SELECT id, firstname, lastname, password 
        FROM " . $this->table_name. "
        WHERE (email = :email AND id = :id)";

        $stmt = $this->conn->prepare($sql);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email',$this->email);
        $stmt->bindParam(':id',$this->id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  // อีเมล์และผู้ใช้นี้มีอยู่ในระบบ
            return true;
        }
        return false;
    }

    // เปลี่ยนรหัสผ่านให้ตาม ไอดีผู้ใช้
    function changePass(){
        $sql="UPDATE " . $this->table_name . "
        SET password = :password 
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':id',$this->id);
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }


    // update a user record
    public function update(){
    
        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";    
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email
                    {$password_set}
                WHERE id = :id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);    
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));    
        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);    
        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }    
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);    
        // execute the query
        if($stmt->execute()){
            return true;
        }    
        return false;
    }


    public function update_type(){    // เปลี่ยนสถานะผู้ใช้ 
        $query = "UPDATE " . $this->table_name . "
                SET type = :type WHERE id = :id";
        $stmt = $this->conn->prepare($query);    
        $this->type=htmlspecialchars(strip_tags($this->type));
        $stmt->bindParam(':type', $this->type);  
        $stmt->bindParam(':id', $this->id);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    // encode to link Email
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
    
        return implode($arrCode);
    }

  
    function sendemail(){        
        require 'config/core.php';
        $id = $this->id;
        $email = htmlspecialchars(strip_tags($this->email)); 
        $name = $this->firstname.' '.$this->lastname;
        //$username = 'คุณ'+$this->firstname+' '+$this->lastname;

        $autoCode = $this->enCodeEmail($id);
        $l = ['a','b','c','d','e','f','0','1','2','3','4','5'];
        $k = $l[rand(0,11)].rand(100, 9999).$l[rand(0,11)].$l[rand(0,11)].rand(100, 9999).$l[rand(0,11)];
        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
           //$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output
            $mail->CharSet = "utf-8";
            $mail->isSMTP();                 //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';      //Set the SMTP server to send through
            $mail->SMTPAuth   = true;     //Enable SMTP authentication
            $mail->Username   = $myEmail;    //SMTP username
            $mail->Password   = $myEmailPass;     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;      //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($myEmail, $myEmailName);
            $mail->addAddress($email, $name);     //Add a recipient
            $mail->addAddress($email);               //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments 
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $email_content = "
                            <!DOCTYPE html>
                            <html>
                                <head>
                                    <meta charset=utf-8'/>
                                    <title>ขอเปลี่ยนรหัสผ่าน</title>
                                </head>
                                <body>
                                    <h1 style='
                                        border-radius: 60px 0 60px 0;
                                        background: #FA420D;
                                        padding: 20px;
                                        margin-bottom:10px;
                                        font-size:30px;
                                        color:white;
                                        text-align: center;' >
                                            I am Served
                                    </h1>
                                    <div style='padding:20px;'>                               
                                        <div>				
                                            <h3><strong style='color:#361C00;'>การขอเปลี่ยนรหัสผ่าน : {$name}</strong></h3>
                                            <a href='{$home}/changepass.php?mode={$autoCode}&key={$k}' target='_blank'>
                                                <h2><strong style='color:#3c83f9;'> >> กรุณาคลิ๊กที่นี่ เพื่อกำหนดรหัสผ่านใหม่ << </strong> </h2>
                                            </a>
                                            <p>** ลิงค์อีเมล์นี้มีอายุไม่เกิน 24 ชั่วโมงนับจากวันเวลาที่ได้รับอีเมล์ </p>
                                        </div>
                                        <div style='margin-top:10px;'>
                                            <hr>
                                            <address>
                                                <h4>ถ้าคุณไม่ได้ทำการขอเปลี่ยนรหัสผ่านเพื่อเข้าระบบ I am Served คุณละทิ้งอีเมล์ฉบับนี้ได้</h4>
                                                <p>ขอขอบพระคุณอย่างสูง</p>
                                                <p>www.facebook.com/surasak.iamserm</p>
                                            </address>
                                        </div>
                                    </div>
                                    <div style='background: #FA420D; color: #FABC0D; padding:20px; text-align:center; border-radius: 0 60px 0 60px;'>
                                            2022 © I am Served Thailand
                                    </div>
                                </body>
                            </html>
                        ";
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'ขอเปลี่ยนรหัสผ่าน';
            //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->msgHTML($email_content);
            $mail->AltBody = 'This is the plain text version of the email content';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            //echo "<h3 class='text-center'>ระบบมีปัญหา กรุณาลองใหม่อีกครั้ง</h3>";
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }     
    }
 
}



