<?php

?>
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
    <!-- navbar 
    <header class="header_area">
      <div class="main_menu">
        <nav class="navbar navbar-expand-md navbar-light d-flex shadow" id="navbar">
          <a class="navbar-brand" href="#" id="logo">
            <img src="./img/iam_logo.png" alt="" width="42" height="42" />
            <span>&nbsp;I am Served</span>
          </a>          
        </nav>
      </div>
    </header> -->
    <!-- /navbar -->
    <div id="siteall">
    <!-- container -->
          <!-- where main content will appear -->
          <div id="content"></div>
      <div class="shop_reg container">
        <div class="row justify-content-md-center">
          <div class="col-md-6">
            <div class="boxshow">
              <i class="close_x fas fa-times" title="Close" id="close_x"></i>
              <div style="text-align: center;"><h3>ลงทะเบียนร้านค้า</h3> </div>              
              <form class="myForm" >
                <div class="form-group">
                  <label for="shopname">ชื่อร้านค้า :</label>
                  <input type="text" class="form-control" id="shopname" required>
                </div>
                <div class="form-group">
                  <label for="shopdesc">รายละเอียดร้านค้า :</label>
                  <textarea class="form-control" id="shopdesc" maxlength="5" placeholder="เกี่ยวกับอะไร ประเภทอะไร อธิบายพอสังเขป"></textarea>
                </div>
                <div class="form-group">
                  <label for="FilePicMain">รูปภาพ ปกหน้าหลัก :</label>
                  <input type="file" class="form-control-file" name="FilePicMain" id="FilePicMain" accept="image/*" onchange="loadFile(event)" >
                  <p><img id="output" width="200"/></p>
                  <a href="#" id="upload">Upload</a>
                  <div id="desc"></div>
                  
                </div>
                <div class="form-group">
                  <label for="shopname">ที่อยู่ร้านค้า :</label>
                  <input type="text" class="form-control" id="shopname" placeholder="เลขที่ หมู่ หมู่บ้าน อาคาร ซอย ถนน.." required>
                </div>
                <div class="form-group">
                  <label for="shopprovince">จังหวัด :</label>
                  <select class="form-control" id="shopprovince" required>
                    <option>--เลือกจังหวัด--</option>
                    <option>2</option>
                    <option>3</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="shopamphures">เขต/อำเภอ :</label>
                  <select class="form-control" id="shopamphures" required>
                    <option>--เลือกเขต/อำเภอ--</option>
                    <option>2</option>
                    <option>3</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="shopdistricts">ตำบล :</label>
                  <select class="form-control" id="shopdistricts" required>
                    <option>--เลือกตำบล--</option>
                    <option>2</option>
                    <option>3</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="shopzip">รหัสไปรษณีย์ :</label>
                  <input type="text" class="form-control" id="shopzip" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" >
                </div>
                
                <div class="form-group" >
                  <label for="shophis">ประวัติ-ความเป็นมาร้านค้า :</label>
                  <textarea class="form-control" id="shophis" placeholder="อธิบายเพื่อให้ลูกค้ารู้จัก ร้านค้ามากขึ้น"></textarea>
                </div>
                <div class="form-group mb-4" >
                <label for="FilePicMain">รูปภาพ ประกอบประวัติร้านค้า</label>
                  <input type="file" id="FilePicHis" accept="image/*" style="display: none;" />
                  <p style="text-align: center;"><img type="image" id="hisPic" src="img/image-not-available.png" width="200px"/></p>
                  <div id="hisdesc" style="text-align: center;"></div>
                  
                </div>
                <div class="form-group">
                  <label for="shoptel">เบอร์โทรศัพท์ :</label>
                  <input type="text" class="form-control" id="shoptel" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" >
                </div>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text" style="color: #00B900; width:45px;  "><i class="fab fa-line fa-lg"></i></div>
                  </div>
                  <input type="text" class="form-control" id="lineid" placeholder=" ID Line ของร้านค้าเพื่อให้ลูกค้าเพิ่มเพื่อน">
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend" >
                    <div class="input-group-text" style="color: #4267B2; font-size:14px; "><i class="fab fa-facebook fa-lg"></i>&nbsp;www.facebook.com/</div>
                  </div>
                  <input type="text" class="form-control" id="facebookid" placeholder=" ID Facebook ของร้านค้าเพื่อให้ลูกค้าเข้าชม">
                </div>
                


                <div style="text-align: center;">
                  <button type='submit' class='btn button primary-button'>บันทึก</button>
                </div>

              </form>
            </div>

          </div>
        </div>

      </div>
    <!-- /container -->
    </div>
    <!-- jQuery & Bootstrap 4 JavaScript libraries -->
    <!-- Jquery js file-->

    <script src="./js/jquery.3.6.0.js"></script>
    <!--<script src="./js/popper.min.js"></script>-->
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/sweetalert2.min.js"></script>
<script>
  $("#hisPic").click(function() {
      $("#FilePicHis").click();
  });

  $(document).on('change',"#FilePicHis",function(){
    var imagehis = document.getElementById('hisPic');
    if(event.target.value.length == 0){
      imagehis.src = "img/image-not-available.png";
      $("#hisdesc").html("");
    }else{
      imagehis.src = URL.createObjectURL(event.target.files[0]);
      var fsize = event.target.files[0].size/1024;
      if (fsize > 2049){
        $("#hisdesc").html("<p style='color:red; font-size: 14px; '>" + (fsize.toFixed(0)) + " Kb (**ไฟล์รูปภาพไม่ควรเกิน: 2048 Kb) </p>");
      }else{ $("#hisdesc").html(""); }
    }
  });

  var loadFile = function(event){
          var image = document.getElementById('output');
          image.src = URL.createObjectURL(event.target.files[0]);
          var fsize = event.target.files[0].size/1024;
          if (event.target.files[0].size > 2097152){
            $("#desc").html("<p style='color:red; font-size: 12px; '>" + (fsize.toFixed(0)) + " Kb (**ไฟล์รูปภาพไม่ควรเกิน: 2048 Kb) </p>");
          }else{ $("#desc").html(""); }
          
        };

    $(document).ready(function(){
        
        /*const element = document.querySelector('#logo');
        element.classList.add('animate__animated', 'animate__bounceInLeft'); 
        element.addEventListener('animationend', () => {
            element.classList.remove('animate__animated', 'animate__bounceInLeft'); 
        });
        $("#logo").hover(function(){               
            element.classList.add('animate__animated', 'animate__jello'); 
            }, function(){                
                element.classList.remove('animate__animated', 'animate__jello'); 
        });*/

        $(document).on("click", "#upload", function () {
          const file1 = document.getElementById('FilePicMain');
          const file2 = document.getElementById('FilePicHis');
          //const files = document.querySelector('[type=file]').files
          const formData = new FormData();
          /*for (let i = 0; i < files.length; i++) {
            let file = files[i]

            formData.append('files[]', file)
          }*/

          formData.append('files[]', file1.files[0]);
          formData.append('files[]', file2.files[0]);
          formData.append('id_shop', '9');

          fetch('api/process_upload.php', {
            method: 'POST',
            body: formData,
          }).then((response) => {
            console.log(response.status)
          })
        });
        

        //================================================================================
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
        var Id = '';
/*
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
        }*/




        //================================================================================
 
        function ckCode_alert($title,$desc) {           
            Swal.fire({
            icon: 'error',
            title: $title,
            text: $desc,            
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
            }).then((result)=> {
                if(result.isConfirmed){
                  console.log('Confirmed');
                }
                window.location.replace("http://localhost:8092/iam");
            })
        }

        function to_alert($icon,$title,$desc) {           
            Swal.fire({
            icon: $icon,
            title: $title,
            text: $desc,            
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
            }).then((result)=> {
                if(result.isConfirmed){
                  console.log('Confirmed');
                }
            })
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
              window.location.replace("http://localhost:8092/iam");        
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
          window.location.replace("http://localhost:8092/iam");        
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
        
        // function to make form values to json format
        $.fn.serializeObject = function () {
          var o = {};
          var a = this.serializeArray();
          $.each(a, function () {
            if (o[this.name] !== undefined) {
              if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
              }
              o[this.name].push(this.value || "");
            } else {
              o[this.name] = this.value || "";
            }
          });
          return o;
        };


    });
</script>

</body>
</html>