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
    <script>
        var count = 0;
    </script>

</head>

<body>

    <div id="siteall">
        <!-- container -->
        <!-- where main content will appear -->
        <div id="content"></div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="fmsearch">
                                <div class="input-group mb-2">
                                    <input type="text" id="search" name="search" class="form-control" placeholder="คำค้นหา.." aria-label="Recipient's username" aria-describedby="button-search">
                                    <button class="btn btn-success" type="button" id="bt_search" name="bt_search" title="ค้นหา"><i class="fas fa-search"></i></button>
                                    <button class="btn btn-primary ml-2" id="bt_add" name="bt_add" style="width: 42px;" type="button" id="button-search" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12" id="addtype"></div>


                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="col-md-12 text-left mb-2">
                        <button type="button" name="Button" class="btn btn-success btn-sm" onClick="fnAddAssetRow();"><i class="fa fa-plus"></i> เพิ่มข้อมูล</button>
                    </div>
                    <table class="table table-striped " id="assetsTab" style="background-color: white;">
                        <thead>
                            <tr class="headings active">
                                <th width="5%" class="text-right">Tool</th>
                                <th style="width:30%">Serial Number</th>
                                <th style="width:25%">Product Name</th>
                                <th>Assets Info</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr id="row1">
                                <td></td>
                                <td>
                                    <div class="input-group" style="margin-bottom:0;">
                                        <input type="text" id="serial1" name="serial1" class="form-control" value="" style="min-width:150px;" readonly />
                                        <input type="hidden" id="assetsid1" name="assetsid[]" value="" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" id="searchIcon1" title="Assets" onclick="assetPickList(this,'1')"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-left">
                                    <div id="productname1" class="text-left">hello</div>
                                </td>
                                <td class="text-left">
                                    <div id="desc1" class="text-left">sawatdee</div>
                                </td>
                                <td class="text-left">
                                    <div id="action1" class="text-center">ok</div>
                                </td> 
                            </tr> 

                        </tbody>
                    </table>



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
    <script src="js/const.js"></script>

    <script>
        /*function deleteRow(rowid)  
        {   
            var rr = 'row'+rowid;
            var row = document.getElementById(rr);
            row.parentNode.removeChild(row);
        }*/

        function deleteRow(rowid) {
            var rr = 'row' + rowid;
            var row = document.getElementById(rr);
            var table = row.parentNode;
            while (table && table.tagName != 'TABLE')
                table = table.parentNode;
            if (!table)
                return;
            table.deleteRow(row.rowIndex);
        }


        function fnAddAssetRow() {

            var tableName = document.getElementById('assetsTab');
            var prev = tableName.rows.length;
            //console.log(prev);
            //var count = eval(prev)-1;//As the table has two headers, we should reduce the count
            count++;
            var row = tableName.insertRow(prev);
            row.id = "row" + count;
            row.style.verticalAlign = "top";

            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);

            /* Product Re-Ordering Feature Code Addition ends */
            //Delete link
            //colone.className = "";
            colone.id = row.id + "_col1";
            colone.innerHTML = `<i class="fa fa-trash" onclick="deleteRow(` + count + `)" style="cursor:pointer;"></i>
            <input id="deleted` + count + `" name="deleted` + count + `" type="hidden" value="0">`;
            colone.style = "text-align: center";

            //Assets Select
            //coltwo.className = ""
            coltwo.innerHTML = `
            <div class="input-group" style="margin-bottom:0;">
                <input type="text" id="serial` + count + `" name="serial` + count + `" class="form-control" value="` + count + `" />
                <input type="hidden" id="assetsid` + count + `" name="assetsid[]" value="" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" id="searchIcon1" title="Assets" onclick="assetPickList(this,` + count + `)" ><i class="fa fa-plus"></i></button>
                </span>
            </div>`;

            //Product Name
            //colthree.className = ""
            colthree.innerHTML = '<div id="productname' + count + '" class="text-left"></div>';

            //Assets Info
            //colfour.className = ""
            colfour.innerHTML = '<div id="desc' + count + '" class="text-left"></div>';

            //Assets Action
            //colfive.className = ""
            colfive.innerHTML = '<div id="action' + count + '" class="text-center"></div>';

            //return count;
        }


        $(document).ready(function() {




            $(document).on("click", "#bt_add", function() {
                showAdd();
            });
            $(document).on("click", "#bt_cancel", function() {
                $("#addtype").html("");

            });
            $(document).on("submit", "#fmadd", function() {
                var add_form = $(this);
                //var jwt = getCookie("jwt");
                var add_form_obj = add_form.serializeObject();
                //add_form_obj.jwt = jwt;
                add_form_obj.acc = "add";
                var form_data = JSON.stringify(add_form_obj);
                $.ajax({
                    url: "api/type_acc.php",
                    type: "POST",
                    contentType: "application/json",
                    data: form_data,
                    success: function(result) {
                        $("#addtype").html("");
                        Signed("success", " บันทึกข้อมูลสำเร็จ ");
                    },
                    error: function(xhr, resp, text) {
                        if (xhr.responseJSON.message == "Unable to create Type.") {
                            Signed("error", " บันทึกข้อมูลไม่สำเร็จ ");
                        } else if (xhr.responseJSON.message == "Type Exit.") {
                            swalertshow('warning', 'บันทึกข้อมูลไม่สำเร็จ', 'ประเภทนี้มีอยู่แล้ว !');
                        } else if (xhr.responseJSON.message == "Unable to access Type.") {
                            Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน");
                        }
                    },
                });
                return false;
            });


            function showAdd() {
                var html = `           
                <form id="fmadd">     
                    <div class="input-group mb-2">
                        <input type="text" id="typename" name="typename" class="form-control" aria-label="Recipient's type" aria-describedby="button-addon" required>
                        <input type="hidden" name="shopid" value="39">
                        <button class="btn btn-success" type="submit" id="bt_addon" name="bt_addon">บันทึก</button>
                        <button class="btn btn-danger ml-2" style="width: 42px;" type="button" id="bt_cancel" name="bt_cancel" title="เพิ่มข้อมูล"><i class="fas fa-times"></i></button>
                    </div>      
                </form>                 
                `;
                $("#addtype").html(html);
                $("#typename").focus();
            }


        });
    </script>

</body>

</html>