function show_type_table(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการสินค้า
    var html = `
  <div class="container">
    <div class="row">                
        <div class="col-lg-6 mx-auto mt-5 animate__animated animate__fadeInDown ">
            <form id="fmsearch_type">
                <div class="input-group mb-2">
                    <input type="text" id="search_type" name="search_type" class="form-control" placeholder="คำค้นหา.." aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_type" name="bt_search_type" title="ค้นหา"><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary ms-3" id="bt_add_type" name="bt_add_type" style="width: 42px;" type="button" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="addtype"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="edittype"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="tabletype"></div>
    </div>
  </div>
    `;
    $("#content").html(html);
    showtypetable(my_shopid,rowperpage,'1'); //<<<<<< แสดงตารางหมวดสินค้า
}

function showtypetable(id,per,p){ //======================== แสดงตารางหมวดสินค้า    
  var ss = document.getElementById('search_type').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST",
    url: "api/data_type.php",
    data: {id:id,search:ss,perpage:per,page:p,jwt:jwt},
    success: function(result){
      var tt=`
      <table class="table animate__animated animate__fadeIn" id="typetable" >
        <thead>
          <tr>
            <th class="text-center" style="width:5%">ลำดับ</th> 
            <th >&nbsp;หมวดสินค้า</th>
            <th >สถานะ</th>
            <th class="text-center">แก้ไข&nbsp;&nbsp;&nbsp;ลบ</th>                
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="mb-3" id="pagination">
      `;              
      $("#tabletype").html(tt);      
      pagination_show(id,p,result.page_all,per,'showtypetable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
          listTypeTable(entry.type_id,entry.type_name,entry.type_st,i); //<<<<<<<<<<<< แสดงรายการทั้งหมด             
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

function listTypeTable(id,nn,st,i){  //=========================== ฟังก์ชั่นเพิ่ม Row ตารางประเเภท
  var tableName = document.getElementById('typetable');
    var status = (st == '0')?"ใช้ปกติ":"ไม่ใช้";
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);
    row.id = "row" + id;
    row.style.verticalAlign = "top";
    
    var col1 = row.insertCell(0);
    var col2 = row.insertCell(1);
    var col3 = row.insertCell(2);
    var collast = row.insertCell(3);
    col1.innerHTML = `<div id="no" class="text-center">`+i+`</div>`;
    col2.innerHTML = `<div id="typename` + id + `" class="text-left">&nbsp;`+nn+`</div>
    <input type="hidden" id="typeid` + id + `" name="typeid` + id + `" value="` + id + `" />
    <input type="hidden" id="typest` + id + `" name="typest` + id + `" value="` + st + `" />
    <input type="hidden" id="typeval` + id + `" name="typeval` + id + `" value="` + nn + `" />`;
    col3.innerHTML = `<div id="st` + id + `" class="text-left">`+status+`</div>`;
    collast.innerHTML = `
    <i class="fas fa-edit me-3" onclick="editTypeRow(` + id + `,`+ my_shopid +`)" style="cursor:pointer; color:var(--success);"></i>
    <i class="fas fa-trash-alt" onclick="deleteTypeRow(` + id + `)" style="cursor:pointer; color:var(--danger);"></i>
    `;           
    collast.style = "text-align: center;";
    if(st!='0'){
      document.getElementById("typename" + id).style.color = "var(--danger)";
      document.getElementById("st" + id).style.color = "var(--danger)";
    }
}

function deleteTypeRow(id){ //================================ ลบข้อมูลในตาราง
  const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
          confirmButton: 'btn button primary-button me-3',
          cancelButton: 'btn button secondary-button ms-3'
      },
      buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
      title: 'โปรดยืนยัน !',
      text: "ต้องการลบข้อมูลหรือไม่? จะส่งผลต่อรายการสินค้าที่ใช้หมวด ที่กำลังจะลบ",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: ' ใช่ ',
      cancelButtonText: ' ไม่ ',
      reverseButtons: false
  }).then((result) => {
      if (result.isConfirmed) {
        var jwt = getCookie("jwt");
        var obj = new Object();
        obj.typeid = id;
        obj.jwt = jwt;
        obj.acc = "del";
        var data = JSON.stringify(obj);
        $.ajax({
            url: "api/type_acc.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(res) {
              swalWithBootstrapButtons.fire(
                  'ข้อมูลถูกลบ!',
                  'ข้อมูลของคุณได้ถูกลบออกจากระบบแล้ว!',
                  'success'
              );  
              showtypetable(my_shopid,rowperpage,'1');
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

function editTypeRow(id,s_id){ //============================== แก้ไขข้อมูลในตาราง            
    var typeval = document.getElementById('typeval'+id).value;
    var typest = document.getElementById('typest'+id).value;
    var html = `        
        <div class="boxshow mb-4 animate__animated animate__fadeIn" style="top: 0.5rem;">
          <i class="close_x fas fa-times" title="Close" id="bt_cancel_edittype"></i>  
          <form class="myForm" id="fmedit_type">
            <div class="form-group mb-3">
              <label for="listname">แก้ไขหมวดสินค้า :</label>
              <input type="text" class="form-control" name="typename" maxlength="100" value="`+typeval+`" required>
            </div>            
            <div class="row ms-2 mb-3">                      
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="typest" value="0" id="typest1" checked>
                <label class="form-check-label" for="typest1">ใช้ปกติ</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="typest" value="1" id="typest2">
                <label class="form-check-label" for="typest2">ไม่ใช้</label>
              </div>
            </div>                       
            <input type="hidden" name="typeid" value="`+id+`">
            <input type="hidden" name="shopid" value="`+s_id+`">
            <div style="text-align: center;">
              <button type='submit' class='btn button primary-button'>บันทึก</button>
            </div>
          </form>     
        </div>           
        `;
    $("#edittype").html(html);
    if(typest == '0'){
      document.getElementById('typest1').checked = true;
    }else{
      document.getElementById('typest2').checked = true;
    }
    $("#addtype").html("");
    $("#typename").focus();
    $("#tabletype").html("");              

}

//=========================== Even เกี่ยวกับ หมวดสินค้า ======================================
$(document).on('click',"#bt_search_type",function () {  //ค้นหาหมวดสินค้า
  $("#edittype").html("");
  $("#addtype").html("");
  $("#bt_add_type").show();
  showtypetable(my_shopid,rowperpage,'1');          
});

$(document).on("click", "#bt_add_type", function() { // แสดงหน้าบันทึกหมวดสินค้า
  var html = `   
        <div class="boxshow mb-4 animate__animated animate__fadeIn" style="top: 0.5rem;">
          <i class="close_x fas fa-times" title="Close" id="bt_cancel_type"></i>  
          <form class="myForm" id="fmadd_type">
            <div class="form-group mb-3">
              <label for="listname">เพิ่มหมวดสินค้า :</label>
              <input type="text" class="form-control" name="typename" maxlength="100" required>
            </div>
            <div class="row ms-2 mb-3">
              <div class="form-check mb-2">
                <input class="form-check-input me-2" type="radio" name="typest" value="0" id="typest1" checked>
                <label class="form-check-label" for="typest1">ใช้ปกติ</label>
              </div>
              <div class="form-check">
                <input class="form-check-input " type="radio" name="typest" value="1" id="typest2">
                <label class="form-check-label" for="typest2">ไม่ใช้</label>
              </div>
            </div>
            <input type="hidden" name="shopid" value="`+my_shopid+`">
            <div style="text-align: center;">
              <button type='submit' class='btn button primary-button'>บันทึก</button>
            </div>
          </form>     
        </div> 
        `;
        $("#addtype").html(html);
        $("#edittype").html("");
        $("#bt_add_type").hide();
        $("#tabletype").html("");  
});

$(document).on("click", "#bt_cancel_type", function() {  // ปิดฟอร์มเพิ่มหมวดสินค้า
    $("#addtype").html("");
    $("#bt_add_type").show();
    showtypetable(my_shopid,rowperpage,'1');
});

$(document).on("click", "#bt_cancel_edittype", function() {  // ปิดฟอร์มเพิ่มหมวดสินค้า
  $("#edittype").html("");
  $("#bt_add_type").show();
  showtypetable(my_shopid,rowperpage,'1');
});

$(document).on("submit", "#fmadd_type", function() {   // บันทึกหมวดสินค้า
    var add_form = $(this);
    var jwt = getCookie("jwt");
    var add_form_obj = add_form.serializeObject();
    add_form_obj.jwt = jwt;
    add_form_obj.acc = "add";
    var form_data = JSON.stringify(add_form_obj);
    $.ajax({
        url: "api/type_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#addtype").html("");
            $("#bt_add_type").show();
            Signed("success", " บันทึกข้อมูลสำเร็จ ");
            showtypetable(my_shopid,rowperpage,'1');
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to create Type.") {
                Signed("error", " บันทึกข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Type Exit.") {
                swalertshow('warning', 'บันทึกข้อมูลไม่สำเร็จ', 'ข้อมูลนี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Type.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});

$(document).on("submit", "#fmedit_type", function() {   // แก้ไขหมวดสินค้า
    var add_form = $(this);
    var jwt = getCookie("jwt");
    var add_form_obj = add_form.serializeObject();
    add_form_obj.jwt = jwt;
    add_form_obj.acc = "up";
    var form_data = JSON.stringify(add_form_obj);
    
    $.ajax({
        url: "api/type_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#edittype").html("");
            Signed("success", "แก้ไขข้อมูลสำเร็จ ");
            showtypetable(my_shopid,rowperpage,'1');
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to update Type.") {
                Signed("error", " แก้ไขข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Type Exit.") {
                swalertshow('warning', 'แก้ไขข้อมูลไม่สำเร็จ', 'ข้อมูลนี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Type.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});