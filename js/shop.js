function showLoginPage() {  //==================== show login page
    // remove jwt
    setCookie("jwt", "", 1);
    $(".mainmenu").hide();
    $("#shopdatamenu").hide();
    $("#accountmenu").hide();
    $("#B_logout").hide();   
       
    // login page html
    var html = `          
    <div class="login container animate__animated animate__fadeInDown">
        <div class="row justify-content-md-center">
        <div class="col-lg-5">
            <div class="boxshow">
            <i class="close_x fas fa-times" title="Close" id="close_x"></i>
            <div style="text-align: center;"><h3>เข้าสู่ระบบ</h3> </div>    
            <form id='login_form' class="myForm">
                <div class='inputWithIcon'>                          
                    <input type='email' id='email' name='email' placeholder='อีเมล์' required>
                    <i class="far fa-envelope" aria-hidden="true"></i>
                </div>
                <div class='inputWithIcon'>                          
                    <input type='password' id='password' name='password' placeholder='รหัสผ่าน'>
                    <i class="fas fa-key" aria-hidden="true"></i>
                </div>
                
                <div class='signuplink'
                    <a>ยังไม่มีบัญชี โปรด.. </a> 
                    <a id="sign_up" href="#">>> ลงทะเบียน <<</a>
                </div>
                <div class='signuplink'
                    <a>ลืมรหัสผ่าน โปรด.. </a> 
                    <a id="pass_req" href="#">>> ขอเปลี่ยนรหัส <<</a>
                </div>
                <div style="text-align: center; margin: 30px 0px 20px 0px;">
                    <button type='submit' class='btn button primary-button'>Login</button>
                </div>
            </form>
            </div>
        </div     
        </div>
    </div>
    
            `;
    $("#content").html(html);

}

function showHomePage() {  //==================================== show home page 
// validate jwt to verify access          
var jwt = getCookie("jwt");
$.post("api/validate_token.php", JSON.stringify({ jwt: jwt }))
    .done(function (result) {
    var u_type = result.data.type;
    var u_id = result.data.id;
    my_id = u_id;
    $("#content").html("");
                    
    if(_shopid[3] !== undefined){ //====== มีการส่งค่า รหัสร้านค้าเข้ามา ======
        $.ajax({
            type: "POST",
            url: "api/data_shop.php",
            data: {id:_shopid[3], mode:'shop'},
            success: function(res){                    
            $("#content").html(home_html);                    
            $('#_bgimage').css("background-image","url(img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random()+")");                       
            $('#_bgimage').css("background-repeat","no-repeat");    
            $('#mainPic').attr("src", "img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random());
            $('#nameShop').text(res[0].shop_name);
            $('#descShop').text(res[0].shop_desc);  
            $('#imgabout').attr("src", "img/pic/mainpic/"+res[0].shop_his_pic+"?v="+Math.random());
            $('#shopabout').text("... "+res[0].shop_his); 
            $('#shopaboutadd').text(res[0].s_address); 
            $('#shop_tel').text(res[0].shop_tel); 
            $('#shop_line').text(res[0].shop_line);
            $('#shop_face').text(res[0].shop_facebook);
            showMyMenu(_shopid[3]);
            }
        });  
        
        // จัดการ เมนู ต่างๆตามประเภทผู้ใช้งาน ==================================
        if(u_type === '1'){   //ผู้ใช้สามารถสร้างร้านค้าได้
        $(".mainmenu").show();                  
        $("#accountmenu").show();

        $("#shopdatamenu").show();
            $("#shop_my").hide();
            $("#shop_scan").hide();
            $("#shop_data_update").hide();
            $("#shop_data_reg").show();   
            $("#shop_listtype").hide();
            $("#shop_list").hide();

        $("#B_logout").show();                                  

        }else if(u_type === '2'){  //ผู้ใช้สร้างร้านค้าแล้วและสามารถแก้ไขข้อมูลร้านค้าได้   
        $(".mainmenu").show();                  
        $("#accountmenu").show();

        $("#shopdatamenu").show();
            $("#shop_my").show();
            $("#shop_scan").hide();
            $("#shop_data_update").show();
            $("#shop_data_reg").hide();
            $("#shop_listtype").show();
            $("#shop_list").show();

        $("#B_logout").show();             
        }else{
        $(".mainmenu").show();                  
        $("#accountmenu").show();

        $("#shopdatamenu").hide();
            $("#shop_my").hide();
            $("#shop_scan").hide();
            $("#shop_data_update").hide();
            $("#shop_data_reg").hide();   
            $("#shop_listtype").hide();
            $("#shop_list").hide();

        $("#B_logout").show();
        }

    }else if(_shopid[3] === undefined || _shopid[3] === null || _shopid[3] === ''){  //===== ไม่มีการส่งรหัสร้านค้าเข้ามา =====
        
        if(u_type === '1'){   //สามารถสร้างร้านค้าได้
        $(".mainmenu").hide();                  
        $("#accountmenu").show();

        $("#shopdatamenu").show();
            $("#shop_my").hide();
            $("#shop_scan").hide();
            $("#shop_data_update").hide();
            $("#shop_data_reg").show();   
            $("#shop_listtype").hide();
            $("#shop_list").hide();

        $("#B_logout").show();                                  

        }else if(u_type === '2'){  //สร้างร้านค้าแล้วและสามารถแก้ไขข้อมูลร้านค้าได้   
        $.ajax({
            type: "POST",
            url: "api/data_shop.php",
            data: {id:u_id, mode:'user'},
            success: function(res){                    
            $("#content").html(home_html);                    
            $('#_bgimage').css("background-image","url(img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random()+")")
            ;          
            $('#_bgimage').css("background-repeat","no-repeat");                   
            $('#mainPic').attr("src", "img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random());
            $('#nameShop').text(res[0].shop_name);
            $('#descShop').text(res[0].shop_desc); 
            $('#imgabout').attr("src", "img/pic/mainpic/"+res[0].shop_his_pic+"?v="+Math.random());
            $('#shopabout').text("... "+res[0].shop_his);
            $('#shopaboutadd').text(res[0].s_address); 
            $('#shop_tel').text(res[0].shop_tel); 
            $('#shop_line').text(res[0].shop_line);
            $('#shop_face').text(res[0].shop_facebook);
            my_shopid = res[0].shop_id; 

            showMyMenu(my_shopid);
            


            }
        });  
                            
        $(".mainmenu").hide();                  
        $("#accountmenu").show();

        $("#shopdatamenu").show();
            $("#shop_my").show();
            $("#shop_scan").hide();
            $("#shop_data_update").show();
            $("#shop_data_reg").hide();   
            $("#shop_listtype").show();
            $("#shop_list").show();

        $("#B_logout").show();             
        }else{
        
        $(".mainmenu").hide();                  
        $("#accountmenu").show();

        $("#shopdatamenu").hide();
            $("#shop_my").hide();
            $("#shop_scan").hide();
            $("#shop_data_update").hide();
            $("#shop_data_reg").hide();   
            $("#shop_listtype").hide();
            $("#shop_list").hide();

        $("#B_logout").show();
        }             
    }             

    
    })
    // show login page on error
    .fail(function (result) {
    showLoginPage();
    Signed('info',' กรุณาเข้าสู่ระบบก่อน ');
    });
}

function showMyShop() {  //======================================= แสดงหน้าร้านค้า 
// validate jwt to verify access          
var jwt = getCookie("jwt");
$.post("api/validate_token.php", JSON.stringify({ jwt: jwt }))
    .done(function (result) {
    var u_type = result.data.type;
    var u_id = result.data.id;
    my_id = u_id;
    $("#content").html("");         
        $.ajax({
            type: "POST",
            url: "api/data_shop.php",
            data: {id:u_id, mode:'user'},
            success: function(res){                    
            $("#content").html(home_html);                    
            $('#_bgimage').css("background-image","url(img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random()+")");                        
            $('#_bgimage').css("background-repeat","no-repeat");   
            $('#mainPic').attr("src", "img/pic/mainpic/"+res[0].shop_main_pic+"?v="+Math.random());
            $('#nameShop').text(res[0].shop_name);
            $('#descShop').text(res[0].shop_desc);  
            $('#imgabout').attr("src", "img/pic/mainpic/"+res[0].shop_his_pic+"?v="+Math.random());
            $('#shopabout').text("... "+res[0].shop_his); 
            $('#shopaboutadd').text(res[0].s_address); 
            $('#shop_tel').text(res[0].shop_tel); 
            $('#shop_line').text(res[0].shop_line);
            $('#shop_face').text(res[0].shop_facebook);
            }
        }); 
        $("#shop_my").hide();
        $("#shop_scan").show();           
    
    })
    // show login page on error
    .fail(function (result) {
    showLoginPage();
    Signed('info',' กรุณาเข้าสู่ระบบก่อน ');
    });
}

function showMyMenu(id) {  //======================================= แสดงเมนูร้านค้า 
      $("#shop_menu").html(`<div class="accordion my-3" id="accordionList"></div>`); 
      $('#accordionList').empty();      
      var i = 0;
      $.ajax({
        type: "POST",
        url: "api/data_type_show.php",
        data: {id:id},
        success: function(result){
          $.each(result, function (key, entry) {
            let s = (i==0)?"show":"";
            $('#accordionList').append(`
            <div class="accordion-item mb-1">
              <div class="accordion-header" id="heading`+entry.type_id+`">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse`+entry.type_id+`" aria-expanded="true" aria-controls="collapse`+entry.type_id+`">
                  `+entry.type_name+`  (`+entry.qty+`)
                </button>
              </div>
          
              <div id="collapse`+entry.type_id+`" class="accordion-collapse collapse `+s+`" aria-labelledby="heading`+entry.type_id+`" data-bs-parent="#accordionList">         
                <div class="accordion-body-fluid row px-0" id="accbody`+entry.type_id+`">   
                
                </div>
              </div>
            </div>`);
            i++;
            var tid = entry.type_id;

            $.ajax({
              type: "POST",
              url: "api/data_list_show.php",
              data: {id:id , idt:tid},
              success: function(res_list){
                $.each(res_list, function (key, entry1) {              
                 let p1 = (parseFloat(entry1.list_p1)) > 0?entry1.list_p1:"";
                  let p2 = (parseFloat(entry1.list_p2)) > 0?"/"+entry1.list_p2:"";
                  let p3 = (parseFloat(entry1.list_p3)) > 0?"/"+entry1.list_p3:"";
                  let p4 = (parseFloat(entry1.list_p4)) > 0?"/"+entry1.list_p4:"";
                  let p = p1+p2+p3+p4; 
                  $('#accbody'+tid).append(`
                  <div class="col-6 col-sm-4 col-md-3 col-lg-2 my-3">
                    <div class="card">
                      <div class="card_image" style="background-image: url('img/pic/listpic/`+entry1.list_pic+`?v=`+Math.random()+`');"></div>        
                      <div class="card-body my-auto">
                        <h3 class="card-title" style="text-align: center;">`+entry1.list_name+`</h3>
                        <p class="card-text" style="text-align: center;">`+entry1.list_desc+`</p>
                      </div>
                      <div class="card-footer bg-transparent" style="text-align: center;">
                        <button class="btn button primary-button" data-bs-toggle="modal" data-bs-target="#addorderModal" 
                        data-bs-idu="`+my_id+`" 
                        data-bs-id="`+id+`" 
                        data-bs-name="`+entry1.list_name+`" 
                        data-bs-desc="`+entry1.list_desc+`" 
                        data-bs-pic='img/pic/listpic/`+entry1.list_pic+`?v=`+Math.random()+`' 
                        data-bs-p1="`+entry1.list_p1+`" 
                        data-bs-p2="`+entry1.list_p2+`" 
                        data-bs-p3="`+entry1.list_p3+`" 
                        data-bs-p4="`+entry1.list_p4+`">`+p+`</button>
                      </div>
                    </div> 
                  </div>   `);
      
                })          
                
                
              }
            });  


          })           
          
        }
      });  

      $("#collapse31").addClass('show');
  
  

  }

  function showShopDataForm() {  //============================== แสดงหน้าลงทะเบียนร้านค้า
    // validate jwt to verify access
    var jwt = getCookie("jwt");
    $.post("api/validate_token.php", JSON.stringify({ jwt: jwt }))
      .done(function (result) {
        // if response is valid, put user details in the form
        var html = `       
        <div class="container">    
            <div class="row justify-content-md-center">
              <div class="col-lg-6">
                <div class="boxshow animate__animated animate__fadeIn" style="margin-bottom:5rem;">
                  <i class="close_x fas fa-times" title="Close" id="close_x"></i>
                  <div style="text-align: center;"><h3>ลงทะเบียนร้านค้า</h3> </div>              
                  <form class="myForm mx-2" id="create_shop_form">
                    <div class="form-group mb-3">
                      <label for="shopname">ชื่อร้านค้า :</label>
                      <input type="text" class="form-control" name="shopname" id="shopname" maxlength="100" required>
                    </div>
                    <div class="form-group mb-3">
                      <label for="shopdesc">รายละเอียดร้านค้า :</label>
                      <textarea class="form-control" name="shopdesc" id="shopdesc" rows="4" maxlength="250" placeholder="เกี่ยวกับอะไร ประเภทอะไร อธิบายพอสังเขป" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                      <label for="FilePicMain">รูปภาพ ปกหน้าหลัก :</label>
                      <input type="file" name="FilePicMain" id="FilePicMain" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
                      <p style="text-align: center;"><img type="image" id="mainPic" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
                      <div id="maindesc" style="text-align: center;"></div>
                    </div>
                    <div class="form-group mb-3">
                      <label for="shopadd">ที่อยู่ร้านค้า :</label>
                      <input type="text" class="form-control" name="shopadd" id="shopadd" maxlength="150" placeholder="เลขที่ หมู่ หมู่บ้าน อาคาร ซอย ถนน.." required>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        <label for="shopprovince">จังหวัด :</label>
                        <select class="form-control" name="shopprovince" id="shopprovince" required>
                          <option selected disabled>--เลือกจังหวัด--</option>                    
                        </select>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label for="shopamphures">เขต/อำเภอ :</label>
                        <select class="form-control" name="shopamphures" id="shopamphures" required>                    
                        </select>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label for="shopdistricts">ตำบล :</label>
                        <select class="form-control" name="shopdistricts" id="shopdistricts">                   
                        </select>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label for="shopzip">รหัสไปรษณีย์ :</label>
                        <input type="text" class="form-control" name="shopzip" id="shopzip" maxlength="5" >
                      </div>
                    </div>

                    <div class="form-group mb-3">
                      <label for="shophis">ประวัติ-ความเป็นมาร้านค้า :</label>
                      <textarea class="form-control" name="shophis" id="shophis" rows="4" maxlength="600" placeholder="อธิบายเพื่อให้ลูกค้ารู้จัก ร้านค้ามากขึ้น"></textarea>
                    </div>
                    <div class="form-group mb-4">
                      <label for="FilePicHis">รูปภาพ ประกอบประวัติร้านค้า :</label>
                      <input type="file" name="FilePicHis" id="FilePicHis" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
                      <p style="text-align: center;"><img type="image" id="hisPic" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
                      <div id="hisdesc" style="text-align: center;"></div>
                    </div>
                    <label for="shoptel">เบอร์โทรศัพท์ :</label>
                    <div class="input-group mb-3">
                    <div class="input-group-text" style="color:var(--text-color); width:45px;"><i class="fas fa-phone-alt fa-lg"></i></div>
                      <input type="text" class="form-control" name="shoptel" id="shoptel" maxlength="15" required>
                    </div>
                    <label for="lineid">ID Line :</label>
                    <div class="input-group mb-3">    
                        <div class="input-group-text" style="color: #00B900; width:45px;"><i class="fab fa-line fa-lg"></i></div>
                      <input type="text" class="form-control" name="lineid" id="lineid" maxlength="100" placeholder="เพื่อให้ลูกค้าเพิ่มเพื่อน">
                    </div>
                    <label for="facebookid">ID Facebook : www.facebook.com/________</label>
                    <div class="input-group mb-4">
                        <div class="input-group-text" style="color: #4267B2; font-size:14px; "><i class="fab fa-facebook fa-lg"></i></div>
                      <input type="text" class="form-control" name="facebookid" id="facebookid" maxlength="100" placeholder="เพื่อให้ลูกค้าเข้าชม">
                    </div>
                    <input type="hidden" name="userid" value="`+ result.data.id +`">
                    <div style="text-align: center;">
                      <button type='submit' class='btn button primary-button'>บันทึก</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
          `;
        $("#content").html(html);
        var file1Max = false; //ไฟล์รูปภาพมีขนาดใหญ่เกินกว่ากำหนดใช่หรือไม่
        var file2Max = false; //ไฟล์รูปภาพมีขนาดใหญ่เกินกว่ากำหนดใช่หรือไม่
        var file1Pic = false;
        var file2Pic = false;
        let dropdown = $('#shopprovince');
        dropdown.empty();
        dropdown.append('<option value="" disabled>--เลือกจังหวัด--</option>');
        dropdown.prop('selectedIndex', 0);
        $.ajax({
                  type: "POST",
                  url: "api/getDropdown.php",
                  data: {id:'',fn:'provinces'},
                  success: function(result){
                    $.each(result, function (key, entry) {
                      dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
                    })                             
                  }
                });              
      })
      .fail(function (result) {
        showLoginPage();
        Signed('warning','กรุณาเข้าสู่ระบบก่อน ');
      });
  }

  function showShopUpdateForm() {  //========================= แสดงหน้าปรับปรุงข้อมูลทะเบียนร้านค้า
    // validate jwt to verify access
    var jwt = getCookie("jwt");
    
    $.post("api/validate_token.php", JSON.stringify({ jwt: jwt }))
      .done(function (result) {
        
        $.ajax({
                  type: "POST",
                  url: "api/data_shop.php",
                  data: {id:result.data.id, mode:'user'},
                  success: function(res){
                    var pic_Main = (res[0].shop_main_pic)?res[0].shop_main_pic:"image-not-available.png";
                    var pic_His = (res[0].shop_his_pic)?res[0].shop_his_pic:"image-not-available.png";
                      // if response is valid, put user details in the form
                      var html = `        
                      <div class="container">              
                          <div class="row justify-content-md-center">
                            <div class="col-lg-6">
                              <div class="boxshow animate__animated animate__fadeIn" style="margin-bottom:5rem;">
                                <i class="close_x fas fa-times" title="Close" id="close_x"></i>
                                <div style="text-align: center;"><h3>ข้อมูลทะเบียนร้านค้า</h3> </div>              
                                <form class="myForm mx-2" id="update_shop_form">
                                  <div class="form-group mb-3">
                                    <label for="shopname">ชื่อร้านค้า :</label>
                                    <input type="text" class="form-control" name="shopname" id="shopname" maxlength="100" value="`+res[0].shop_name+`" required>
                                  </div>
                                  <div class="form-group mb-3">
                                    <label for="shopdesc">รายละเอียดร้านค้า :</label>
                                    <textarea class="form-control" name="shopdesc" id="shopdesc" rows="4" maxlength="250" placeholder="เกี่ยวกับอะไร ประเภทอะไร อธิบายพอสังเขป" required>`+res[0].shop_desc+`</textarea>
                                  </div>
                                  <div class="form-group mb-3">
                                    <label for="FilePicMain">รูปภาพ ปกหน้าหลัก :</label>
                                    <input type="file" name="FilePicMain" id="FilePicMain" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
                                    <p style="text-align: center;"><img type="image" id="mainPic" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
                                    <div id="maindesc" style="text-align: center;"></div>
                                  </div>
                                  <div class="form-group mb-3">
                                    <label for="shopadd">ที่อยู่ร้านค้า :</label>
                                    <input type="text" class="form-control" name="shopadd" id="shopadd" maxlength="150" placeholder="เลขที่ หมู่ หมู่บ้าน อาคาร ซอย ถนน.." value="`+res[0].shop_add+`" required>
                                  </div>

                                  <div class="row">
                                    <div class="col-sm-6 mb-3">
                                      <label for="shopprovince">จังหวัด :</label>
                                      <select class="form-control" name="shopprovince" id="shopprovince" required>
                                        <option selected disabled>--เลือกจังหวัด--</option>                    
                                      </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                      <label for="shopamphures">เขต/อำเภอ :</label>
                                      <select class="form-control" name="shopamphures" id="shopamphures" required>                    
                                      </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                      <label for="shopdistricts">ตำบล :</label>
                                      <select class="form-control" name="shopdistricts" id="shopdistricts">                   
                                      </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                      <label for="shopzip">รหัสไปรษณีย์ :</label>
                                      <input type="text" class="form-control" name="shopzip" id="shopzip" maxlength="5" value="`+res[0].shop_zip+`">
                                    </div>
                                  </div>

                                  <div class="form-group mb-3">
                                    <label for="shophis">ประวัติ-ความเป็นมาร้านค้า :</label>
                                    <textarea class="form-control" name="shophis" id="shophis" rows="4" maxlength="600" placeholder="อธิบายเพื่อให้ลูกค้ารู้จัก ร้านค้ามากขึ้น">`+res[0].shop_his+`</textarea>
                                  </div>
                                  <div class="form-group mb-4">
                                    <label for="FilePicHis">รูปภาพ ประกอบประวัติร้านค้า :</label>
                                    <input type="file" name="FilePicHis" id="FilePicHis" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
                                    <p style="text-align: center;"><img type="image" id="hisPic" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
                                    <div id="hisdesc" style="text-align: center;"></div>
                                  </div>
                                  <label for="shoptel">เบอร์โทรศัพท์ :</label>
                                  <div class="input-group mb-3">
                                  <div class="input-group-text" style="color: var(--text-color); width:45px;"><i class="fas fa-phone-alt fa-lg"></i></div>
                                    <input type="text" class="form-control" name="shoptel" id="shoptel" maxlength="15" value="`+res[0].shop_tel+`" required>
                                  </div>
                                  <label for="lineid">ID Line :</label>
                                  <div class="input-group mb-3">    
                                      <div class="input-group-text" style="color: #00B900; width:45px;"><i class="fab fa-line fa-lg"></i></div>
                                    <input type="text" class="form-control" name="lineid" id="lineid" maxlength="100" placeholder="เพื่อให้ลูกค้าเพิ่มเพื่อน" value="`+res[0].shop_line+`">
                                  </div>
                                  <label for="facebookid">ID Facebook : www.facebook.com/________</label>
                                  <div class="input-group mb-4">
                                      <div class="input-group-text" style="color: #4267B2; font-size:14px; "><i class="fab fa-facebook fa-lg"></i></div>
                                    <input type="text" class="form-control" name="facebookid" id="facebookid" maxlength="100" placeholder="เพื่อให้ลูกค้าเข้าชม" value="`+res[0].shop_facebook+`">
                                  </div>
                                  <input type="hidden" name="shopid" value="`+res[0].shop_id+`">
                                  <div style="text-align: center;">
                                    <button type='submit' class='btn button primary-button'>บันทึก</button>
                                  </div>

                                </form>
                              </div>
                            </div>
                          </div>     
                        </div>                    
                        `;
                      $("#content").html(html);
                      
                      $(function() {
                        $('#mainPic').attr("src", "img/pic/mainpic/"+pic_Main+"?v="+Math.random());
                        $('#hisPic').attr("src", "img/pic/mainpic/"+pic_His+"?v="+Math.random());                              
                      })
                      var file1Max = false; //ไฟล์รูปภาพมีขนาดใหญ่เกินกว่ากำหนดใช่หรือไม่
                      var file2Max = false; //ไฟล์รูปภาพมีขนาดใหญ่เกินกว่ากำหนดใช่หรือไม่
                      var file1Pic = false;
                      var file2Pic = false;
                      
                      $.ajax({
                        type: "POST",
                        url: "api/getDropdown.php",
                        data: {id:'',fn:'provinces'},
                        success: function(result){
                          let dropdown = $('#shopprovince');
                          dropdown.empty();
                          $.each(result, function (key, entry) {
                            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
                          })
                          $("#shopprovince option[value='"+res[0].shop_province+"']").attr("selected","selected");                             
                        }
                      });

                      $.ajax({
                        type: "POST",
                        url: "api/getDropdown.php",
                        data: {id:res[0].shop_province,fn:'provinces_sel'},
                        success: function(result){
                          let dropdown = $('#shopamphures');
                          dropdown.empty();
                          $.each(result, function (key, entry) {
                            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
                          })
                          $("#shopamphures option[value='"+res[0].shop_amphure+"']").attr("selected","selected");                             
                        }
                      });

                      $.ajax({
                        type: "POST",
                        url: "api/getDropdown.php",
                        data: {id:res[0].shop_amphure,fn:'amphures_sel'},
                        success: function(result){
                          let dropdown = $('#shopdistricts');
                          dropdown.empty();
                          $.each(result, function (key, entry) {
                            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
                          })
                          $("#shopdistricts option[value='"+res[0].shop_district+"']").attr("selected","selected");                                                          
                        }
                      });
                      
                  }
                });
        
      })

      .fail(function (result) {
        showLoginPage();
        Signed('warning','กรุณาเข้าสู่ระบบก่อน ')              
      });
  }

//=========================================================================================
//========================  Event การจัดการร้านค้า  ===========================================
//=========================================================================================
$(document).on("click", "#mainPic", function () {  //=========== เลือกรูปภาพหน้าปก ร้านค้า
  $("#FilePicMain").click();
});

$(document).on('change',"#FilePicMain",function(event){
  var imagemain = document.getElementById('mainPic');
  if(event.target.value.length == 0){
    imagemain.src = "img/image-not-available.png";
    $("#maindesc").html("");
    file1Pic = false;
  }else{
    imagemain.src = URL.createObjectURL(event.target.files[0]);
    var fsize = event.target.files[0].size/1024;
    if (fsize > 2049){
      $("#maindesc").html("<p style='color:red; font-size: 14px; '>" + (fsize.toFixed(0)) + " Kb (**ไฟล์รูปภาพไม่ควรเกิน: 2048 Kb) </p>");
      file1Max = true;              
    }else{ 
      $("#maindesc").html(""); 
      file1Max = false;              
    }
    file1Pic = true;
  }
});

$(document).on("click", "#hisPic", function () {  //=========== เลือกรูปภาพประกอบประวัติร้านค้า
  $("#FilePicHis").click();
});

$(document).on('change',"#FilePicHis",function(event){
  var imagehis = document.getElementById('hisPic');
  if(event.target.value.length == 0){
    imagehis.src = "img/image-not-available.png";
    $("#hisdesc").html("");
    file2Pic = false;
  }else{
    imagehis.src = URL.createObjectURL(event.target.files[0]);
    var fsize = event.target.files[0].size/1024;
    if (fsize > 2049){
      $("#hisdesc").html("<p style='color:red; font-size: 14px; '>" + (fsize.toFixed(0)) + " Kb (**ไฟล์รูปภาพไม่ควรเกิน: 2048 Kb) </p>");
      file2Max = true;
    }else{ 
      $("#hisdesc").html(""); 
      file2Max = false;
    }
    file2Pic = true;
  }
});

$(document).on("change", "#shopprovince", function () { //================ เลือก จังหวัด
  var id_sel = $(this).val();
  $.ajax({
      type: "POST",
      url: "api/getDropdown.php",
      data: {id:id_sel,fn:'provinces_sel'},
      success: function(result){
        let dropdown = $('#shopamphures');
        dropdown.empty();              
        dropdown.append('<option value="" disabled>--เลือกเขต/อำเภอ--</option>');
        dropdown.prop('selectedIndex', 0);
        $.each(result, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
        });                 
        
          $('#shopdistricts').html(''); 
          $('#shopdistricts').val('');  
          $('#shopzip').val(''); 
          $('#shopamphures').focus();
      }
    });
});

$(document).on("change", "#shopamphures", function () { //============= เลือก อำเภอ
  var id_sel = $(this).val();
  $.ajax({
      type: "POST",
      url: "api/getDropdown.php",
      data: {id:id_sel,fn:'amphures_sel'},
      success: function(result){
        let dropdown = $('#shopdistricts');
        dropdown.empty();              
        dropdown.append('<option value="" disabled>--เลือกตำบล--</option>');
        dropdown.prop('selectedIndex', 0);
        $.each(result, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name_th));
        }); 
        $('#shopdistricts').focus();               
      }
    });
});

$(document).on("change", "#shopdistricts", function () { //========== เลือก ตำบล
  var id_sel = $(this).val();
  $.ajax({
      type: "POST",
      url: "api/getDropdown.php",
      data: {id:id_sel,fn:'districts_sel'},
      success: function(result){
        let textin = $('#shopzip');
        $.each(result, function (key, entry) {
          textin.val(entry.zip_code);
        });
        $('#shophis').focus(); 
      }
    });
});

$(document).on("submit", "#create_shop_form", function () {  //============= บันทึกลงทะเบียน ร้านค้า   
  if(!file1Pic || !file2Pic){
    swalertshow('warning','กรุณาเลือกรูปภาพ !','รูปภาพปก และรูปประกอบประวัติร้านค้า'); 
    return false;
  }else if(file1Max || file2Max){
    swalertshow('warning','รูปภาพไม่ถูกต้อง !','รูปมีขนาดไฟล์ใหญ่เกินกว่าที่กำหนด'); 
    return false;
  }
  var create_shop_form = $(this);
  var jwt = getCookie("jwt");
  var create_shop_form_obj = create_shop_form.serializeObject();
  create_shop_form_obj.jwt = jwt;
  var form_data = JSON.stringify(create_shop_form_obj);
    // submit form data to api            
    $.ajax({
      url: "api/create_shop.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        setCookie("jwt", result.jwt, 1);
        if(result.id_shop){
          let ids = result.id_shop;
          while(ids.length < 9){ids = '0'+ids;}
          
          let file1 = document.getElementById('FilePicMain');        
          if(file1.files.length > 0){
            let formData1 = new FormData();
            formData1.append('files', file1.files[0]);        
            formData1.append('id_shop', ids);
            formData1.append('key', 'main');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพปกขึ้น Server
              method: 'POST',
              body: formData1,
            }).then((response) => {
              console.log(response.statusText)
            })
          }
          
          let file2 = document.getElementById('FilePicHis');        
          if(file2.files.length > 0){
            let formData2 = new FormData();
            formData2.append('files', file2.files[0]);        
            formData2.append('id_shop', ids);
            formData2.append('key', 'his');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพประวัติขึ้น Server
              method: 'POST',
              body: formData2,
            }).then((response) => {
              console.log(response.statusText)
            })
          }
                            
          Signed("success","กำลังลงทะเบียนร้านค้า...");
          setTimeout(function(){ showHomePage(); }, 2300);

        }else{
          swalertshow('error','ลงทะเบียน ไม่สำเร็จ!','รูปภาพไม่สามารถส่งขึ้นระบบโปรดลองใหม่'); 
        }
      },

      // show error message to user
      error: function (xhr, resp, text) {
        if (xhr.responseJSON.message == "User already has a shop.") {
          Signed("error","ผู้ใช้ได้มีการลงทะเบียนร้านค้าไว้แล้ว");

        } else if (xhr.responseJSON.message == "Name already exists.") {  
          swalertshow('error','ลงทะเบียน ไม่สำเร็จ!','ชื่อร้านค้านี้มีอยู่แล้วในระบบ');  

        } else {
          //showLoginPage();
          Signed("warning","ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน");
        }
      },
    });

  return false;
});

$(document).on("submit", "#update_shop_form", function () { //=========== บันทึกแก้ไขข้อมูล ร้านค้า         
  // get form data
  var update_shop_form = $(this);
  var jwt = getCookie("jwt");
  var update_shop_form_obj = update_shop_form.serializeObject();
  update_shop_form_obj.jwt = jwt;
  var form_data = JSON.stringify(update_shop_form_obj);
  $("#file-progress-bar").width('0%');
    $.ajax({              
      url: "api/update_shop.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      
      success: function (result) {
        
        // tell the user account was updated                
        if(result.id_shop){
          let ids = result.id_shop;
          while(ids.length < 9){ids = '0'+ids;}
          
          let file1 = document.getElementById('FilePicMain');        
          if(file1.files.length > 0){
            let formData1 = new FormData();
            formData1.append('files', file1.files[0]);        
            formData1.append('id_shop', ids);
            formData1.append('key', 'main');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพปกขึ้น Server
              method: 'POST',
              body: formData1,
            }).then((response) => {
              console.log(response.statusText)
            })
          }
          
          let file2 = document.getElementById('FilePicHis');        
          if(file2.files.length > 0){
            let formData2 = new FormData();
            formData2.append('files', file2.files[0]);        
            formData2.append('id_shop', ids);
            formData2.append('key', 'his');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพประวัติขึ้น Server
              method: 'POST',
              body: formData2,
            }).then((response) => {
              console.log(response.statusText)
            })
          }
                            
          Signed("success","กำลังปรับปรุงข้อมูล...");
          setTimeout(function(){ showHomePage(); }, 2300);
          
        }else{
          swalertshow('error','ปรับปรุงข้อมูล ไม่สำเร็จ!','รูปภาพไม่สามารถส่งขึ้นระบบโปรดลองใหม่'); 
        }

      },              
      error: function (xhr, resp, text) { // show error message to user
        if (xhr.responseJSON.message == "Name already exists.") {  
          swalertshow('error','ลงทะเบียน ไม่สำเร็จ!','ชื่อร้านค้านี้มีอยู่แล้วในระบบ');  

        } else {
          //showLoginPage();
          Signed("warning","ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน"+xhr.responseJSON.error);
        }
      },
    });

  return false;
});


var addOrderModal = document.getElementById('addorderModal')  // แสดงหน้าเพิ่มคำสั่งซื้อเมื่อเลือกรายการจากเมนูสินค้า
  addOrderModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    const listprice = [];
    var button = event.relatedTarget;
    // Extract info from data-bs-* attributes
    var idlist = button.getAttribute('data-bs-id');
    var iduser = button.getAttribute('data-bs-idu');
    var idshop = button.getAttribute('data-bs-ids');
    var listname = button.getAttribute('data-bs-name');
    var listdesc = button.getAttribute('data-bs-desc');
    var listpic = button.getAttribute('data-bs-pic');
    listprice[0] = button.getAttribute('data-bs-p1');
    listprice[1] = button.getAttribute('data-bs-p2');
    listprice[2] = button.getAttribute('data-bs-p3');
    listprice[3] = button.getAttribute('data-bs-p4');

    let dropdown = $('#addordprice');
    dropdown.empty();
    for (i=0; i < 4; i++){
      if(parseFloat(listprice[i]) > 0){
        dropdown.append($('<option></option>').attr('value', listprice[i]).text(parseFloat(listprice[i]).toFixed(2)+' บาท'));
      }
    }
    $("#addordprice option").first().attr("selected","selected"); 
    $('#addordname').text(listname);
    $('#addorddesc').text(listdesc);
    $('#addordPic').attr("src", listpic);
    document.getElementById('addordqty').value = "1";
    document.getElementById('addordcom').value = "";
    var btnAdd = document.getElementById('addorder');
    btnAdd.setAttribute("data-bs-id",idlist);
    btnAdd.setAttribute("data-bs-name",listname);
    btnAdd.setAttribute("data-bs-price",dropdown.val());
    btnAdd.setAttribute("data-bs-qty",document.getElementById('addordqty').value);
    btnAdd.setAttribute("data-bs-com",document.getElementById('addordcom').value);
})

$(document).on("change", "#addordprice", function () { //========== เลือก ราคา
  document.getElementById('addorder').setAttribute("data-bs-price",$(this).val()); 
});

$(document).on("change", "#addordqty", function () { //========== เมื่อเปลี่ยนจำนวน
  document.getElementById('addorder').setAttribute("data-bs-qty",$(this).val()); 
});

$(document).on("change", "#addordcom", function () { //========== เมื่อเปลี่ยนหมายเหตุ
  document.getElementById('addorder').setAttribute("data-bs-com",$(this).val()); 
});

var OrderModal = document.getElementById('orderModal')  // เพิ่มรายการไปยังออร์เดอร์
  OrderModal.addEventListener('show.bs.modal', function (event) {
    

});

$('#addorder').on('click', function () { // เพิ่มรายการไปยังออร์เดอร์
  list_ord++;
    // Extract info from data-bs-* attributes
    let idlist = $(this).attr('data-bs-id');
    let listname = $(this).attr('data-bs-name');
    let listprice = $(this).attr('data-bs-price');
    let listqty = $(this).attr('data-bs-qty');
    let sum_list = parseFloat(listprice)*parseInt(listqty);
    let listcom = $(this).attr('data-bs-com');

    var tableName = document.getElementById('ordtable');
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);
    row.id = "row" + idlist;
    row.style.verticalAlign = "top";
    var col1 = row.insertCell(0);
    var col2 = row.insertCell(1);
    var col3 = row.insertCell(2);
    var col4 = row.insertCell(3);
    var col5 = row.insertCell(4);
    var col6 = row.insertCell(5);
    var collast = row.insertCell(6);
    col1.innerHTML = `<div id="list_no" class="text-center">`+list_ord+`</div>`;
    col2.innerHTML = `<div id="list_name` + idlist + `" >&nbsp;`+listname+`</div>`;
    col3.innerHTML = `<div id="list_price` + idlist + `" style="text-align: right;">`+parseFloat(listprice).toFixed(2)+`</div>`;
    col4.innerHTML = `<div id="list_qty` + idlist + `" style="text-align: right;">`+listqty+`</div>`;
    col5.innerHTML = `<div id="list_sum` + idlist + `" style="text-align: right;">`+sum_list.toFixed(2)+`</div>`;
    col6.innerHTML = `<div id="list_com` + idlist + `" style="text-align: center;">`+listcom+`</div>`;
    collast.innerHTML = `
    <i class="fas fa-edit me-3" onclick="editListRow(` + idlist + `)" style="cursor:pointer; color:var(--success);"></i>
    <i class="fas fa-trash-alt" onclick="delOrdRow(` + idlist + `)" style="cursor:pointer; color:var(--danger);"></i>
    `;           
    collast.style = "text-align: center;";
});

function delOrdRow(rowid) { // ลบรายการออกจากหน้าออร์เดอร์
  var rr = 'row' + rowid;
  var row = document.getElementById(rr);
  var table = row.parentNode;
  while (table && table.tagName != 'TABLE')
      table = table.parentNode;
  if (!table)
      return;
  table.deleteRow(row.rowIndex);
  list_ord--;
}
