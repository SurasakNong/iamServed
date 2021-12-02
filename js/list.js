function show_list_table(){ //==============================แสดงค้นหา และปุ่มเพิ่ม รายการสินค้า
    var html = `   
  <div class="container">
    <div class="row">          
        <div class="col-lg-6 animate__animated animate__fadeInDown mx-auto mt-5">
            <form id="fmsearch_list">
                <div class="input-group mb-2">
                    <input type="text" id="search_list" name="search_list" class="form-control" placeholder="คำค้นหา.." aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_list" title="ค้นหา"><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary ms-3" id="bt_add_list" style="width: 42px;" type="button" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="addlist"></div>
    </div> 
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="editlist"></div>
    </div> 
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="tablelist"></div>
    </div> 
  </div>
    `;
    $("#content").html(html);
    showlisttable(my_shopid,rowperpage,'1');
  }

function showlisttable(id,per,p){ //======================== แสดงตารางรายการสินค้า    
  $("#tablelist").html("");   
  var ss = document.getElementById('search_list').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST",
    url: "api/data_list.php",
    data: {id:id,search:ss,perpage:per,page:p,jwt:jwt,key:"show"},
    success: function(result){
      var tt=`
      <table class="table animate__animated animate__fadeIn" id="listtable" >
        <thead>
          <tr>
            <th class="text-center" style="width:5%">ลำดับ</th> 
            <th >&nbsp;รายการสินค้า</th>
            <th >&nbsp;หมวด</th>
            <th >&nbsp;สถานะ</th>
            <th class="text-center">แก้ไข&nbsp;&nbsp;&nbsp;ลบ</th>                
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="mb-3" id="pagination">
      `;              
      $("#tablelist").html(tt);      
      pagination_show(id,p,result.page_all,per,'showlisttable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
          listTable(entry.list_id,entry.list_name,entry.type_name,entry.list_st,i); //<<<<<<<<<<<< แสดงรายการทั้งหมด             
      });             
    },
    error: function(xhr, resp, text) {
        if (xhr.responseJSON.message == "Access denied.") {
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");            
        }else{
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");
        }
    }
  });
}

function listTable(id,nn,nn2,st,i){  //=========================== ฟังก์ชั่นเพิ่ม Row ตารางรายการสินค้า
  var tableName = document.getElementById('listtable');
  var status = (st === '0')?"ใช้ปกติ":"ไม่ใช้";
  var prev = tableName.rows.length;           
  var row = tableName.insertRow(prev);
  row.id = "row" + id;
  row.style.verticalAlign = "top";
  var col1 = row.insertCell(0);
  var col2 = row.insertCell(1);
  var col3 = row.insertCell(2);
  var col4 = row.insertCell(3);
  var collast = row.insertCell(4);
  col1.innerHTML = `<div id="no" class="text-center">`+i+`</div>`;
  col2.innerHTML = `<div id="listname` + id + `" class="text-left">&nbsp;`+nn+`</div>`;
  col3.innerHTML = `<div id="typename` + id + `" class="text-left">`+nn2+`</div>`;
  col4.innerHTML = `<div id="listst` + id + `" class="text-left">`+status+`</div>`;
  collast.innerHTML = `
  <i class="fas fa-edit me-3" onclick="editListRow(` + id + `)" style="cursor:pointer; color:var(--success);"></i>
  <i class="fas fa-trash-alt" onclick="deleteListRow(` + id + `)" style="cursor:pointer; color:var(--danger);"></i>
  `;           
  collast.style = "text-align: center;";
  if(st!=='0'){
    document.getElementById("listname" + id).style.color = "var(--danger)";
    document.getElementById("typename" + id).style.color = "var(--danger)";
    document.getElementById("listst" + id).style.color = "var(--danger)";
  }
}

function deleteListRow(id){ //================================ ลบข้อมูลในตาราง
  const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
          confirmButton: 'btn button primary-button me-3',
          cancelButton: 'btn button secondary-button ms-3'
      },
      buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
      title: 'โปรดยืนยัน !',
      text: "ต้องการลบข้อมูลหรือไม่ ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: ' ใช่ ',
      cancelButtonText: ' ไม่ ',
      reverseButtons: false
  }).then((result) => {
      if (result.isConfirmed) {
        var jwt = getCookie("jwt");
        var obj = new Object();
        obj.listid = id;
        obj.jwt = jwt;
        obj.acc = "del";
        var data = JSON.stringify(obj);
        $.ajax({
            url: "api/list_acc.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(res) {
              swalWithBootstrapButtons.fire(
                  'ข้อมูลถูกลบ!',
                  'ข้อมูลของคุณได้ถูกลบออกจากระบบแล้ว!',
                  'success'
              );               
              console.log(res.message);
              showlisttable(my_shopid,rowperpage,'1');
            },
            error: function(xhr, resp, text) {
                if (xhr.responseJSON.message == "Unable to delete Type.") {
                    Signed("error", "ลบข้อมูลไม่สำเร็จ !="+xhr.responseJSON.code);
                } else if (xhr.responseJSON.message == "Unable to access Type.") {
                    Signed("error", "ไม่สามารถดำเนินการลบข้อมูลได้ โปรดลองใหม่!");
                }else{
                  showLoginPage();
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
                }
            },
        });
        
          
      } else if ( result.dismiss === Swal.DismissReason.cancel ){
          swalWithBootstrapButtons.fire(
              'ยกเลิก',
              'ข้อมูลของคุณยังไม่ถูกลบ :)',
              'error'
          )
      }
  })
}

function editListRow(id){ //============================== แก้ไขข้อมูลในตาราง      
  var jwt = getCookie("jwt");  
  $.ajax({
    type: "POST",
    url: "api/data_list.php",
    data: {id:id,jwt:jwt,key:"edit"}, 
    success: function(result){
      var ed=`
      <div class="boxshow animate__animated animate__fadeIn mb-5">
      <i class="close_x fas fa-times" title="Close" id="bt_cancel_list"></i>  
      <form class="myForm" id="fmedit_list">
        <div class="form-group mb-3">
          <label for="FilePiclist">รูปภาพสินค้า :</label>
          <input type="file" name="FilePiclist" id="FilePiclist" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
          <p style="text-align: center;"><img type="image" id="listPic" src="img/pic/listpic/image-not-available.png" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
          <div id="listdesc" style="text-align: center;"></div>
        </div>
        <div class="form-group mb-3">
          <label for="listname">ชื่อสินค้า :</label>
          <input type="text" class="form-control" name="listname" id="listname" maxlength="100" value="`+result.data[0].list_name+`" required>
        </div>
        <div class="form-group mb-3">
          <label for="listdesc">รายละเอียดสินค้า :</label>
          <textarea class="form-control" name="listdesc" id="listdesc" rows="3" maxlength="250" placeholder="อธิบายพอสังเขป" required>`+result.data[0].list_desc+`</textarea>
        </div>    
        <div class="form-group mb-3" >
          <label for="typelist">หมวดสินค้า :</label>
          <select class="form-control" name="typelist" id="typelist" required>
            <option selected disabled>--เลือกหมวดสินค้า--</option>                    
          </select>
        </div>
        <div class="row">
          <div class="col-sm-6 mb-3">
            <label for="listp1">ราคาที่ 1 :</label>
            <input type="number" class="form-control" name="listp1" id="listp1" min="0" step="0.05" value="`+result.data[0].list_p1+`" required>
          </div>
          <div class="col-sm-6 mb-3">
            <label for="listp2">ราคาที่ 2 :</label>
            <input type="number" class="form-control" name="listp2" id="listp2" min="0" step="0.05" value="`+result.data[0].list_p2+`">
          </div>
          <div class="col-sm-6 mb-3">
            <label for="listp3">ราคาที่ 3 :</label>
            <input type="number" class="form-control" name="listp3" id="listp3" min="0" step="0.05" value="`+result.data[0].list_p3+`">
          </div>
          <div class="col-sm-6 mb-3">
            <label for="listp4">ราคาที่ 4 :</label>
            <input type="number" class="form-control" name="listp4" id="listp4" min="0" step="0.05" value="`+result.data[0].list_p4+`">
          </div>
        </div>

          <div class="row ms-2 mb-3">                      
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="listst" value="0" id="listst1" checked>
                <label class="form-check-label" for="listst1">ใช้ปกติ</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="listst" value="1" id="listst2">
                <label class="form-check-label" for="listst2">ไม่ใช้</label>
              </div>
          </div>       
          
        <input type="hidden" name="listid" value="`+id+`">
        <input type="hidden" name="shopid" value="`+my_shopid+`">
        <div style="text-align: center;">
          <button type='submit' class='btn button primary-button'>บันทึก</button>
        </div>

      </form>     
    </div> 
      `;              
           
      $("#addlist").html("");
      $("#tablelist").html(""); 
      $("#editlist").html(ed)
      $(function() {
        $('#listPic').attr("src", "img/pic/listpic/"+result.data[0].list_pic+"?v="+Math.random());                           
      })
      if(result.data[0].list_st === '0'){
        document.getElementById('listst1').checked = true;
      }else{
        document.getElementById('listst2').checked = true;
      }
      $("#listname").focus(); 
      let dropdown = $('#typelist');
      dropdown.empty();
      dropdown.append('<option value="" disabled>--เลือกหมวดสินค้า--</option>');
      dropdown.prop('selectedIndex', 0);
      $.ajax({
          type: "POST",
          url: "api/getDropdown.php",
          data: {id:my_shopid,fn:'type'},
          success: function(res){
            $.each(res, function (key, entry) {
              dropdown.append($('<option></option>').attr('value', entry.type_id).text(entry.type_name));
            })
            $("#typelist option[value='"+result.data[0].type_id+"']").attr("selected","selected");                             
          }
      });
                  
    },
    error: function(xhr, resp, text) {
        if (xhr.responseJSON.message === "Access denied.") {
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");            
        }else{
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");
        }
    }
  });      
           
}

  //=========================== Even เกี่ยวกับ รายการสินค้า ======================================
$(document).on('click',"#bt_search_list",function () {  //ค้นหาหมวดสินค้า
  $("#editlist").html("");
  showlisttable(my_shopid,rowperpage,'1');          
});

$(document).on("click", "#bt_add_list", function() { //===========  แสดงหน้าบันทึกเพิ่มหมวดสินค้า
  $("#editlist").html("");
  var html = `         
    <div class="boxshow animate__animated animate__fadeIn mb-5">
      <i class="close_x fas fa-times" title="Close" id="bt_cancel_list"></i>  
      <form class="myForm" id="fmadd_list">
        <div class="form-group mb-3">
          <label for="FilePiclist">รูปภาพสินค้า :</label>
          <input type="file" name="FilePiclist" id="FilePiclist" accept="image/png, image/gif, image/jpeg, image/jpg" style="display: none;"/>
          <p style="text-align: center;"><img type="image" id="listPic" src="img/pic/listpic/image-not-available.png" style="cursor:pointer; max-width: 70%; height: auto;"/></p>
          <div id="listdesc" style="text-align: center;"></div>
        </div>
        <div class="form-group mb-3">
          <label for="listname">ชื่อสินค้า :</label>
          <input type="text" class="form-control" name="listname" id="listname" maxlength="100" value="" required>
        </div>
        <div class="form-group mb-3">
          <label for="listdesc">รายละเอียดสินค้า :</label>
          <textarea class="form-control" name="listdesc" id="listdesc" rows="3" maxlength="250" placeholder="อธิบายพอสังเขป" required></textarea>
        </div>    
        <div class="form-group mb-3" >
          <label for="typelist">หมวดสินค้า :</label>
          <select class="form-control" name="typelist" id="typelist" required>
            <option selected disabled>--เลือกหมวดสินค้า--</option>                    
          </select>
        </div>          

          <div class="row">
            <div class="col-sm-6 mb-3">
              <label for="listp1">ราคาที่ 1 :</label>
              <input type="number" class="form-control" name="listp1" id="listp1" min="0" step="0.05" required>
            </div>
            <div class="col-sm-6 mb-3">
              <label for="listp2">ราคาที่ 2 :</label>
              <input type="number" class="form-control" name="listp2" id="listp2" min="0" step="0.05" >
            </div>
            <div class="col-sm-6 mb-3">
              <label for="listp3">ราคาที่ 3 :</label>
              <input type="number" class="form-control" name="listp3" id="listp3" min="0" step="0.05">
            </div>
            <div class="col-sm-6 mb-3">
              <label for="listp4">ราคาที่ 4 :</label>
              <input type="number" class="form-control" name="listp4" id="listp4" min="0" step="0.05">
            </div>
          </div>

          <div class="row ms-2 mb-3">                      
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="listst" value="0" id="listst1" checked>
                <label class="form-check-label" for="listst1">ใช้ปกติ</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="listst" value="1" id="listst2">
                <label class="form-check-label" for="listst2">ไม่ใช้</label>
              </div>
          </div>            
        
        <input type="hidden" name="shopid" value="`+my_shopid+`">
        <div style="text-align: center;">
          <button type='submit' class='btn button primary-button'>บันทึก</button>
        </div>

      </form>     
    </div>          
    `;
    $("#addlist").html(html);
    $("#bt_add_list").hide();
    $("#tablelist").html("");   
    var file1Max = false; //ไฟล์รูปภาพมีขนาดใหญ่เกินกว่ากำหนดใช่หรือไม่
    var file1Pic = false;
    let dropdown = $('#typelist');
    dropdown.empty();
    dropdown.append('<option value="" disabled>--เลือกหมวดสินค้า--</option>');
    dropdown.prop('selectedIndex', 0);
    $.ajax({
        type: "POST",
        url: "api/getDropdown.php",
        data: {id:my_shopid,fn:'typeadd'},
        success: function(result){
          $.each(result, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.type_id).text(entry.type_name));
          })                             
        }
    });
});


$(document).on("click", "#listPic", function () {  //========= เลือกรูปภาพประกอบประวัติร้านค้า
  $("#FilePiclist").click();
});

$(document).on('change',"#FilePiclist",function(event){ //========= เลือกรูปภาพสินค้า
  var imagelist = document.getElementById('listPic');
  if(event.target.value.length == 0){
    imagelist.src = "img/image-not-available.png";
    $("#listdesc").html("");
    file1Pic = false;
  }else{
    imagelist.src = URL.createObjectURL(event.target.files[0]);
    var fsize = event.target.files[0].size/1024;
    if (fsize > 2049){
      $("#listdesc").html("<p style='color:red; font-size: 14px; '>" + (fsize.toFixed(0)) + " Kb (**ไฟล์รูปภาพไม่ควรเกิน: 2048 Kb) </p>");
      file1Max = true;              
    }else{ 
      $("#listdesc").html(""); 
      file1Max = false;              
    }
    file1Pic = true;
  }
});  

$(document).on("click", "#bt_cancel_list", function() {  //=========== ปิดฟอร์มเพิ่มรายการสินค้า
  $("#addlist").html("");
  $("#editlist").html("");
  $("#bt_add_list").show();
  showlisttable(my_shopid,rowperpage,'1'); 
});

$(document).on("submit", "#fmadd_list", function () {  //============= บันทึกรายการสินค้า 
  if(!file1Pic){
    swalertshow('warning','กรุณาเลือกรูปภาพ !','รูปภาพปก และรูปประกอบประวัติร้านค้า'); 
    return false;
  }else if(file1Max){
    swalertshow('warning','รูปภาพไม่ถูกต้อง !','รูปมีขนาดไฟล์ใหญ่เกินกว่าที่กำหนด'); 
    return false;
  }
  var add_list_form = $(this);
  var jwt = getCookie("jwt");
  var add_list_form_obj = add_list_form.serializeObject();
  add_list_form_obj.jwt = jwt;
  add_list_form_obj.acc = "add";
  var form_data = JSON.stringify(add_list_form_obj);
    // submit form data to api            
    $.ajax({
      url: "api/list_acc.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        if(result.list_id){
          let list_id = result.list_id;
          while(list_id.length < 9){list_id = '0'+list_id;}
          
          let file1 = document.getElementById('FilePiclist');        
          if(file1.files.length > 0){
            let formData = new FormData();
            formData.append('files', file1.files[0]);        
            formData.append('id_list', list_id);
            formData.append('key', 'list');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพสินค้า Server
              method: 'POST',
              body: formData,
            }).then((response) => {
              console.log(response.statusText);
            })
          }                    
          $("#addlist").html("");
          $("#bt_add_list").show();
          Signed("success","เพิ่มรายการสินค้าสำเร็จ!");
          showlisttable(my_shopid,rowperpage,'1'); 

        }else{
          swalertshow('error','เพิ่มสินค้าไม่สำเร็จ !','รูปภาพสินค้าไม่สามารถส่งขึ้นระบบ โปรดลองใหม่'); 
        }
      },

      // show error message to user
      error: function (xhr, resp, text) {
        if (xhr.responseJSON.message == "Unable to create List.") {
          Signed("error","การร้องขอเพื่อเพิ่มข้อมูลผิดพลาด !");

        } else if (xhr.responseJSON.message == "List Exit.") {  
          swalertshow('error','เพิ่มสินค้า ไม่สำเร็จ!','ชื่อสินค้านี้มีอยู่แล้วในระบบ');  

        } else {
          showLoginPage();
          Signed("warning","โปรดเข้าสู่ระบบก่อน");
        }
      },
    });

  return false;
});

$(document).on("submit", "#fmedit_list", function() {   // แก้ไขรายการสินค้า
  var edit_list_form = $(this);
  var jwt = getCookie("jwt");
  var edit_list_form_obj = edit_list_form.serializeObject();
  edit_list_form_obj.jwt = jwt;
  edit_list_form_obj.acc = "up";
  var form_data = JSON.stringify(edit_list_form_obj);
    // submit form data to api            
    $.ajax({ 
      url: "api/list_acc.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        if(result.list_id){
          let list_id = result.list_id;
          while(list_id.length < 9){list_id = '0'+list_id;}
          
          let file1 = document.getElementById('FilePiclist');        
          if(file1.files.length > 0){
            let formData = new FormData();
            formData.append('files', file1.files[0]);        
            formData.append('id_list', list_id);
            formData.append('key', 'list');
            fetch('api/pic_upload.php', { //อัพโหลดรูปภาพสินค้า Server
              method: 'POST',
              body: formData,
            }).then((response) => {
              console.log(response.statusText);
            })
          }                  
          $("#editlist").html("");
          $("#bt_add_list").show();
          Signed("success","แก้ไขรายการสินค้าสำเร็จ!");
          showlisttable(my_shopid,rowperpage,'1'); 

        }else{
          swalertshow('error','แก้ไขข้อมูลไม่สำเร็จ !','รูปภาพสินค้าไม่สามารถส่งขึ้นระบบ โปรดลองใหม่'); 
        }

        
      },

      // show error message to user
      error: function (xhr, resp, text) {
        if (xhr.responseJSON.message == "Unable to create List.") {
          Signed("error","การร้องขอเพื่อเพิ่มข้อมูลผิดพลาด !");

        } else if (xhr.responseJSON.message == "List Exit.") {  
          swalertshow('error','เพิ่มสินค้า ไม่สำเร็จ!','ชื่อสินค้านี้มีอยู่แล้วในระบบ');  
        } else if (xhr.responseJSON.message == "Access denied.") {  
            showLoginPage();
            Signed("warning","โปรดเข้าสู่ระบบก่อน");
        } else {
          swalertshow('error','เพิ่มสินค้า ไม่สำเร็จ!',xhr.responseJSON.message); 
        }
        
      },
    });

  return false;
});

 