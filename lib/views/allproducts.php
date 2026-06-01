<?php 
//start session
session_start();
// if(isset($_SESSION['user'])){
//     if(isset($_SESSION['usertype'])){

//         $usertype = $_SESSION['usertype'];
//             if($usertype == "employee"){
              
//             }else{
//             header('Location:../../login.php');
//         }

//     }else{
//         header('Location:../../login.php');
//     }

// }else{
//     header('Location:../../login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../css/fontawesome-free-7.1.0-web/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/fontawesome-free-7.1.0-web/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <?php
      include_once('common.php')
      ?>
      <style>
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 6px;
        background: #f5f5f5;
        padding: 5px;
    }
    .table tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}
</style>
</head>


<body>

   

    <main class="app-main">
 
<div class="d-flex justify-content-center">
    <div class="card p-4 shadow" style="width: 900px;">
        <h4 class="text-center mb-4">All Products</h4>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead class="table-success">
                    <tr>
                        
                        <th>Product Name</th>
                        <th>Category Name</th>
                        <th>Image</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productlist"></tbody>
            </table>
        </div>
    </div>
</div>

    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/sweetalert2.js"></script>
    <script>
        $(document).ready(function () {
            function loadcatdropdown() {
                $.get("../routes/category/loadcatdropdown.php", function (res) {
                    $('#productCategory').html(
                        "<option disabled selected value=''>Select Product Category</option>" + res);
                });
            }


           
            function allproductdata() {
                $('#productlist').html("");
                $.get("../routes/product/allproduct.php", function (res) {
                    $('#productlist').html(res);
                });
            }

            // product already existed
            function product_exist() {
                Swal.fire({
                    icon: 'error',
                    title: 'Product already exists!',
                    draggable: true,
                    backdrop: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#productName').attr('class', 'form-control is-invalid');
                    }
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: message,
                    draggable: true,
                    showConfirmButton: true
                });
            }

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    draggable: true
                });
            }



            // loadproductdata();
            allproductdata();
            loadcatdropdown();
            $('#editprbtn').hide();

            $('#productImg').change(function () {
                var fileread = new FileReader();
                fileread.onload = function (e) {
                    $('#imgprev').attr('src', e.target.result);
                    $('#imgprev').attr('style', 'height:200px; width:200px;');
                }
                fileread.readDataURL(this.files[0]);
            });






            $(document).on('click', '.editbtn', function () {
                let productId = $(this).data('id');
                $.get("../routes/product/loadproductbyid.php", {
                    //userid: $userid
                    pid: productId
                }, function (res) {

                    var jdata = jQuery.parseJSON(res);

                    $('#productName').val(jdata.productName);
                    $('#productCategory').val(jdata.productCategory);
                    $('#productSupplier').val(jdata.productSupplier);
                    $('#productDescription').val(jdata.productDescription);
                    $('#productPrice').val(jdata.productPrice);
                    $('#priceType').val(jdata.priceType);
                    $('#pid').val(jdata.productId);

                    $('#imgprev').attr('src', '../' + jdata.productImg);
                    $('#imgprev').attr('style', 'height:200px; width:200px;');

                    $('#editprbtn').show();
                    $('#addprbtn').hide();
                })
            });




            $('#addprbtn').on('click', function (e) {
                e.preventDefault();
                var form = $('#addPrForm')[0];
                var formData = new FormData(form);

                let prName = $('#productName').val();
                let prCat = $('#productCategory').val();
                let prSup = $('#productSupplier').val();
                let prDes = $('#productDescription').val();
                let prPrice = $('#productPrice').val();
                let prPriceType = $('#priceType').val();
                let prImg = $('#productImg').val();
                let error = 0;
                if (prName == "") {
                    $('#productName').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productName').attr('class', 'form-control is-valid');
                }

                if (prCat == null) {
                    $('#productCategory').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productCategory').attr('class', 'form-control is-valid');
                }

                if (prSup == "") {
                    $('#productSupplier').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productSupplier').attr('class', 'form-control is-valid');
                }

                if (prDes == "") {
                    $('#productDescription').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productDescription').attr('class', 'form-control is-valid');
                }

                if (prPrice == "") {
                    $('#productPrice').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productPrice').attr('class', 'form-control is-valid');
                }

                if (prPriceType == null) {
                    $('#priceType').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#priceType').attr('class', 'form-control is-valid');
                }

                if (prImg == "") {
                    $('#productImg').attr('class', 'form-control is-invalid');
                    error++;
                } else {
                    $('#productImg').attr('class', 'form-control is-valid');
                }

                // var form = $('#addPrForm')[0];
                //   var formData = new FormData(form);

                //       if (error === 0) {
                //         $.ajax({
                //           url: "../routes/product/addProduct.php",
                //           type: 'POST',
                //           data: formData,
                //           processData: false,
                //           contentType: false,
                //           success: function (data) {
                //   console.log(data);
                //   if (data.status == true) {
                //     Swal.fire({
                //       icon: 'success',
                //       title: 'Product Added Successfully!',
                //       draggable: true
                //     });

                //     $('#addPrForm')[0].reset();
                //     $('#imgprev').attr('src', "");

                //     loadproductdata();

                //   } else {
                //     Swal.fire({
                //       icon: 'error',
                //       title: 'Failed to Add Product'
                //     });
                //   }
                // }, 
                //           error: function (data) {

                //           }
                // });
                //       }

                if (error === 0) {

                    $.ajax({
                        url: "../routes/product/addProduct.php",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (response) {
                            console.log(response);
                            if (response.status == true) {
                                showSuccess(response.message ||
                                    'Product Added Successfully!');
                                $('#addPrForm')[0].reset();
                                $('#imgprev').attr('src', "");
                                loadproductdata();

                            } else if (response.error_type === "product_exists") {
                                product_exist()
                            } else {
                                showError(response.message || 'Failed to Add Product');
                            }
                        },
                        error: function (data) {

                        }
                    });
                }



            });






            $('#editprbtn').on('click', function (e) {
                e.preventDefault();

                var form = $('#editPrForm')[0];
                var formData = new FormData(form);

                $.ajax({
                    url: "../routes/product/editProduct.php",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            $('#editprbtn').hide();
                            $('#addprbtn').show();
                            $('#addPrForm')[0].reset();
                            $('#pid').val(''); //from gpt
                            loadalldata();
                            $('#imgprev').attr('src', "");
                            $('#imgprev').attr('style', '0px; 0px;');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Update Product'
                            });
                        }
                    },
                    error: function (data) {

                    }

                });
            });





        });
    </script>


</body>

</html>