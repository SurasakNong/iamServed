<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/icon_iam.ico" />
    <title>I am Served</title>
    <!-- Reset CSS -->
    <link rel="stylesheet" href="./css/reset.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css" />

    <!-- Fonts awesome icons -->
    <link rel="stylesheet" href="./css/all.min.css" />

    <!-- Sweet alert2 and animate css file-->
    <link rel="stylesheet" href="./css/sweetalert2.min.css" />
    <link rel="stylesheet" href="./css/animate.css/animate.min.css" />

    <!-- cusstom css file -->
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <!-- navbar -->
    <header class="header_area">
      <div class="main_menu">
        <nav class="navbar navbar-expand-md navbar-light d-flex" id="navbar">
          <a class="navbar-brand" href="#" id="logo">
            <img src="./img/iam_logo.png" alt="" width="42" height="42" />
            <span>&nbsp;I am Served</span>
          </a>          
        </nav>
      </div>
    </header>
    <!-- /navbar -->
    <div id="siteall">
    <!-- container -->
          <!-- where main content will appear -->
          <div id="content"></div>
    <!-- /container -->
    </div>
    <!-- jQuery & Bootstrap 4 JavaScript libraries -->
    <!-- Jquery js file-->

    <script src="./js/jquery.3.6.0.js"></script>
    <!--<script src="./js/popper.min.js"></script>-->
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/sweetalert2.min.js"></script>
    <script src="./js/const.js"></script>
<script>
    $(document).ready(function(){
        
        const element = document.querySelector('#logo');
        element.classList.add('animate__animated', 'animate__bounceInLeft'); 
        element.addEventListener('animationend', () => {
            element.classList.remove('animate__animated', 'animate__bounceInLeft'); 
        });
        $("#logo").hover(function(){               
            element.classList.add('animate__animated', 'animate__jello'); 
            }, function(){                
                element.classList.remove('animate__animated', 'animate__jello'); 
        });

        //================================================================================
        
        var Id = '';

        if(!_get('id')){
          if(_get('mode') && (_get('mode').length > 10) && _get('key')){ // ใช้ตรวจสอบ ค่าของตัวแปร mode และ key
            var in_key = _get('mode'); 
            var n_key = in_key.length;
            var nId = parseInt(in_key.substr(n_key-2,2));
            Id = in_key.substr(n_key-2-nId,nId);
            var nTime = parseInt(in_key.substr(n_key-2-nId-2,2));
            var _Time = in_key.substr(n_key-2-nId-2-nTime,nTime);
            var _Times = _Time *1000
            //var endTime = new Date(_Times);
            var nowTime = new Date().getTime();
            //var nowD = new Date(nowTime);
            var cTime = parseInt(in_key.substr(0,2));
            var C_Time = 0;
            for(i=0; i<6; i++){
              C_Time = C_Time + parseInt(_Time.substr(nTime-(6-i),1));
            }

            if((cTime != C_Time) || (n_key != (6+nId+nTime))) {
              ckCode_alert('ลิงค์นี้ไม่ถูกต้อง..','กรุณาตรวจสอบลิงค์ที่ได้รับทางอีเมล์ใหม่ หรือขอเปลี่ยนรหัสใหม่อีกครั้ง');            
            }else if(nowTime > _Times){
              ckCode_alert('ลิงค์นี้หมดอายุแล้ว','กรุณาขอเปลี่ยนรหัสใหม่อีกครั้ง');
            }else{
              showChangepass();
            } 

          }else{                             
            ckCode_alert('ลิงค์นี้ไม่ถูกต้อง.','กรุณาตรวจสอบลิงค์ที่ได้รับทางอีเมล์ใหม่ หรือขอเปลี่ยนรหัสใหม่อีกครั้ง'); 
          }
        }

 
        // trigger when Change Password form is submitted
        $(document).on("submit", "#login_form", function () {
          // get form data
          var ch_pass_form = $(this);
          var form_data = JSON.stringify(ch_pass_form.serializeObject());

          // submit form data to api
          $.ajax({
            url: "api/changepass_data.php",
            type: "POST",
            contentType: "application/json",
            data: form_data,
            success: function (result) {              
              window.location.replace(home);        
              sign_up_form.find("input").val("");
            },
            error: function (xhr, resp, text) {
              // on error, tell the user sign up failed
              if (xhr.responseJSON.message == "Unable to change password.") {
                to_alert("error"," เปลี่ยนรหัสผ่านไม่สำเร็จ ","ระบบขัดข้อง โปรดลองใหม่อีกครั้ง");
              } else if (xhr.responseJSON.message == "Id and Email not found.") {   
                to_alert("error"," เปลี่ยนรหัสผ่านไม่สำเร็จ ","อีเมล์ นี้ไม่มีอยู่ในระบบ");
              } else if (xhr.responseJSON.message == "No Email.") {   
                to_alert("error"," เปลี่ยนรหัสผ่านไม่สำเร็จ ","โปรดระบุอีเมล์");
              } else {
                to_alert("error"," เปลี่ยนรหัสผ่านไม่สำเร็จ ","ระบบขัดข้อง โปรดลองใหม่อีกครั้ง");
              }
            },
          });

          return false;
        });

        // to login page
        $(document).on("click", "#login", function () {
          window.location.replace(home);        
          sign_up_form.find("input").val("");
        });

        // show login page
        function showChangepass() {

          // Change Password page html
          var html = `          
          <div class="login container">
            <div class="row">
                <div class="login-bg">
                    <h2>เปลี่ยนรหัสผ่าน</h2>
                    <form id='login_form' class="login-box">
                        <div class='inputWithIcon'>                          
                            <input type='email' id='email' name='email' placeholder='อีเมล์' required>
                            <i class="far fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class='inputWithIcon'>                          
                            <input type='password' id='password' name='password' placeholder='รหัสผ่านใหม่' required>
                            <i class="fas fa-key" aria-hidden="true"></i>
                        </div>                    
                        <input type='hidden' name='id' value="`+ Id +`" />
                        <div class='signuplink'
                          <a>หน้าหลัก... </a> 
                          <a id="login" href="#"> << เข้าสู่ระบบ >> </a>
                        </div>
                        <button type='submit' class='btn button primary-button'>บันทึก</button>
                    </form>
                </div>     
            </div>
          </div>
                `;
          $("#content").html(html);
          $("#email").focus();
          
        } 
        
      
    });
</script>
</body>
</html>