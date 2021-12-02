function sign_up(){ //===================== แสดงฟอร์มลงทะเบียนผู้ใช้งานใหม่
    var html = `
          <div class="sign_up container animate__animated animate__fadeInDown">
            <div class="row justify-content-md-center">
              <div class="col-lg-6">
                <div class="boxshow">
                  <i class="close_x fas fa-times" title="Close" id="close_x"></i>
                  <div style="text-align: center;"><h3>ลงทะเบียนสมาชิก</h3> </div>
                  <form id='sign_up_form' class="myForm">
                      <div class='inputWithIcon'>                         
                          <input type="text" name="firstname" id="firstname" placeholder='ชื่อ' required />
                          <i class="far fa-address-card"></i>
                      </div>
      
                      <div class='inputWithIcon'> 
                          <input type="text" name="lastname" id="lastname" placeholder='นามสกุล' required />
                          <i class="far fa-address-card"></i>
                      </div>
      
                      <div class='inputWithIcon'> 
                          <input type="email" name="email" id="email" placeholder='อีเมล์' required />
                          <i class="far fa-envelope"></i>
                      </div>
      
                      <div class='inputWithIcon'> 
                          <input type="password" name="password" id="password" placeholder='ตั้งรหัสผ่าน' required />
                          <i class="fas fa-key"></i>
                      </div>
                      <div class='signuplink'
                        <a>มีบัญชีอยู่แล้ว โปรด.. </a> 
                        <a id="login" href="#"> >> เข้าสู่ระบบ <<</a>
                      </div>
                      <div style="text-align: center; margin: 30px 0px 20px 0px;">
                        <button type='submit' class='btn button primary-button'>บันทึก</button>
                      </div>
                  </form>
                </div>  
              </div>
            </div>
          </div>
                `;
          $("#content").html(html);
}

function showUpdateAccountForm() { //====================== แสดงฟอร์มปรับปรุงบัญชีผู้ใช้งาน
    // validate jwt to verify access
    var jwt = getCookie("jwt");
    $.post("api/validate_token.php", JSON.stringify({ jwt: jwt }))
        .done(function (result) {
        myarr[0] = (result.data.firstname);
        myarr[1] = (result.data.lastname);
        myarr[2] = (result.data.email);
        // if response is valid, put user details in the form
        var html = `
    <div class="sign_up container animate__animated animate__fadeInDown">
        <div class="row justify-content-md-center">
        <div class="col-lg-6">
            <div class="boxshow">
            <i class="close_x fas fa-times" title="Close" id="close_x"></i>
            <div style="text-align: center;"><h3>ปรับปรุงบัญชี</h3> </div>
            <form id='update_account_form' class="sign_up-box">
                <div class='inputWithIcon'>                         
                    <input type="text" name="firstname" id="firstname" placeholder='ชื่อ' required value="` +
            result.data.firstname +
            `" />
                    <i class="far fa-address-card"></i>
                </div>

                <div class='inputWithIcon'> 
                    <input type="text" name="lastname" id="lastname" placeholder='นามสกุล' required value="` +
            result.data.lastname +
            `" />
                    <i class="far fa-address-card"></i>
                </div>

                <div class='inputWithIcon'> 
                    <input type="email" name="email" id="email" placeholder='อีเมล์' required value="` +
            result.data.email +
            `" />
                    <i class="far fa-envelope"></i>
                </div>

                <div class='inputWithIcon'> 
                    <input type="password" name="password" id="password" placeholder='ตั้งรหัสผ่าน' />
                    <i class="fas fa-key"></i>
                </div>                   
                <div style="text-align: center; margin: 30px 0px 20px 0px;">
                    <button type='submit' class='btn button primary-button'>บันทึก</button>
                </div>
            </form>
            </div>  
        </div>
        </div>
    </div>
            `;
        $("#content").html(html);
        })

        // on error/fail, tell the user he needs to login to show the account page
        .fail(function (result) {
        showLoginPage();
        Signed('warning','กรุณาเข้าสู่ระบบก่อน ')
        });
}

//============================= Event ผู้ใช้งาน =================================================

$(document).on("click", "#sign_up", function () {
    sign_up();
  });

  $(document).on("submit", "#sign_up_form", function () {  //===== บันทึกลงทะเบียนผู้ใช้งาน
    // get form data
    var sign_up_form = $(this);
    var form_data = JSON.stringify(sign_up_form.serializeObject());
    // submit form data to api
    $.ajax({
      url: "api/create_user.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        // if response is a success, tell the user it was a successful sign up & empty the input boxes
        Signed('success',' ลงทะเบียนสำเร็จแล้ว.. โปรดเข้าสู่ระบบ ');
        showLoginPage();
        sign_up_form.find("input").val("");
      },
      error: function (xhr, resp, text) {
        if (xhr.responseJSON.message == "Unable to create user.") {
          swalertshow("error","ลงทะเบียน ไม่สำเร็จ","โปรดตรวจสอบ หรือลองใหม่อีกครั้ง !");
        } else if (xhr.responseJSON.message == "Email Exit.") {  
          swalertshow("error","ลงทะเบียน ไม่สำเร็จ","อีเมล์นี้มีการใช้ลงทะเบียนไว้แล้ว !");           
        }
      },
    });
    return false;
  });

  $(document).on("submit", "#login_form", function () { //================= ทำการเข้าสู่ระบบ
    // get form data
    var login_form = $(this);
    var form_data = JSON.stringify(login_form.serializeObject());
    // submit form data to api
    $.ajax({
      url: "api/login.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        
        // store jwt to cookie
        setCookie("jwt", result.jwt, 1);

        // show home page & tell the user it was a successful login
        showHomePage();          
        Signed("success"," เข้าสู่ระบบสำเร็จ ");
      },
      error: function (xhr, resp, text) {
        // on error, tell the user login has failed & empty the input boxes
        swalertshow('warning','เข้าสู่ระบบไม่สำเร็จ','อีเมล์ หรือ รหัสผ่าน ไม่ถูกต้อง');              
        login_form.find("input").val("");              
      },
    });
    return false;
  });

  

  $(document).on("submit", "#update_account_form", function () { //======== ทำการบันทึกปรับปรุงข้อมูลผู้ใช้งาน
    // handle for update_account_form
    var update_account_form = $(this);
    // validate jwt to verify access
    var jwt = getCookie("jwt");
    // get form data
    var update_account_form_obj = update_account_form.serializeObject();
    // add jwt on the object
    update_account_form_obj.jwt = jwt;
    // convert object to json string
    var form_data = JSON.stringify(update_account_form_obj);
    var pass = document.getElementById('password');
    if((myarr[0]==update_account_form_obj.firstname) && (myarr[1]==update_account_form_obj.lastname) 
        && (myarr[2]==update_account_form_obj.email) && (pass.value == '')){  
        showHomePage();  
    }else{
      // submit form data to api            
      $.ajax({
        url: "api/update_user.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function (result) {
          // tell the user account was updated
          Signed("success"," ปรับปรุงบัญชีสำเร็จ ");
          showHomePage();          
        /* $("#response").html(
            "<div class='alert alert-success'>Account was updated.</div>"
          );*/
          // store new jwt to coookie
          setCookie("jwt", result.jwt, 1);
        },

        // show error message to user
        error: function (xhr, resp, text) {
          if (xhr.responseJSON.message == "Unable to update user.") {
            Signed("error"," ปรับปรุงบัญชีไม่สำเร็จ ");

          } else if (xhr.responseJSON.message == "This Email to used.") {  
            swalertshow('warning','ปรับปรุงข้อมูลไม่สำเร็จ','อีเมล์ มีผู้อื่นใช้งานอยู่แล้ว !');  

          } else if (xhr.responseJSON.message == "Access denied.") {
            showLoginPage();
            Signed("warning","ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน");
          }
        },
      });
    }
    return false;
  });
  
  $(document).on("click","#pass_req", function(){  //========= ขอเปลี่ยนรหัสผ่านผู้ใช้งาน
    var in_email = document.getElementById("email").value;
    if(in_email == ""){
      swalertshow("warning","ขอเปลี่ยนรหัสผ่านไม่สำเร็จ","กรุณาระบุอีเมล์ของคุณก่อน!");
    } else{
      Swal.fire({
      title: 'ยืนยันการขอเปลี่ยนรหัส ?',
      text: "เมื่อตอบตกลง ระบบจะส่งลิงค์ไปยังอีเมล์ของคุณ",
      icon: 'question',
      customClass: {
        confirmButton: 'btn button primary-button',
        cancelButton: 'btn button secondary-button'
          },                
      buttonsStyling: false,
      showCancelButton: true,
      /*confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',*/
      cancelButtonText: 'ยกเลิก',
      confirmButtonText: 'ตกลง'
      }).then((result) => {
        if (result.isConfirmed) { //เมื่อทำการยืนยันการขอเปลี่ยนพาสเวริด
          //var form_input = $(this);
          //var form_data = JSON.stringify(form_input.serializeObject());
          var form_data = '{"email":"'+in_email+'"}';                
          
          Swal.fire({
            title: 'กำลังส่งลิงค์ ไปยังอีเมล์คุณ!',
            html: 'โปรดรอสักครู่....',
            timer: 40000,
            timerProgressBar: false,
            didOpen: () => {
              Swal.showLoading()
              // submit form data to api
              $.ajax({  
                url: "api/changepass_req.php",
                type: "POST",
                contentType: "application/json",
                data: form_data,
                success: function (result) {      
                  swal.close();                                
                  swalertshow("success","ระบบส่งอีเมล์ สำเร็จ","อีเมล์ได้ส่งถึงคุณแล้ว โปรดตรวจสอบ และอาจอยู่ในถังขยะเนื่องจากเข้าใจผิดว่าเป็นสแปม"); 
                },
                error: function (xhr, resp, text) {
                  swal.close(); 
                  // on error, tell the user sign up failed
                  if (xhr.responseJSON.message == "Unable to send Email.") {
                    swalertshow("error","ระบบส่งอีเมล์ ไม่สำเร็จ","โปรดตรวจสอบอีเมล์ของคุณ หรือลองใหม่อีครั้ง !");
                  } else if (xhr.responseJSON.message == "Email not found.") {  
                    
                    swalertshow("error","ขอเปลี่ยนรหัส ไม่สำเร็จ","ไม่พบ อีเมล์นี้ ในระบบ !");                        
                  } else{
                    swalertshow("error","ขอเปลี่ยนรหัส ไม่สำเร็จ","Unable to Access !");
                  }
                },
              });
              
            },
            willClose: () => {
              console.log('after closed')                    
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              console.log('I was closed by the timer 40 sec')
            }
          })

        }
      })       
      
    }
  });
