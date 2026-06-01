<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    header('Location: ../../login.php');
    exit;
}

?>

<!doctype html>
<html lang="en">

<head></head>

<body>
  <title>Product Management</title>


  <?php
      include_once('common.php')
      ?>

  <main class="app-main">
    <h4>Product Management</h4>
    <div class="row px-2">

      <div class="col-6 px-4 card mt-2 mb-2" style="height: 800px;">
        <form id="addPrForm">
          <h2 class="text-center my-4">Add Product</h2>
          <div>
            <label for="productName" class="form-label mt-2">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName"
              placeholder="Enter Product Name">
            <input type="hidden" id="pid" name="pid">
          </div>
          <div>
            <label for="productCategory" class="form-label mt-2">Product Category</label>
            <select class="form-select" name="productCategory" id="productCategory">
              <!-- // <option disable selected value=''>Select Product Category</option> -->

            </select>
          </div>
          <div>
            <label for="productSupplier" class="form-label mt-2">Product Supplier</label>
            <select class="form-select" name="productSupplier" id="productSupplier">
              <option disable selected value=''>Select Product Supplier</option>
              <option value='Anchor'>Anchor</option>
              <option value='Maliban'>Maliban</option>
              <option value='Maliban'>Munchee</option>
              <option value='Kist'>Kist</option>
              <option value='MD'>MD</option>
              <option value='Elephant House'>Elephant House</option>
              <option value='Uniliver'>Uniliver</option>
              <option value='Marvel'>Marvel</option>
              <option value='Atlas'>Atlas</option>
            </select>
          </div>
          <div>
            <label for="productDescription" class="form-label mt-2">Product Description</label>
            <input type="text" class="form-control" id="productDescription" name="productDescription"
              placeholder="Enter Product Description">
          </div>
          <div>
            <label for="productPrice" class="form-label mt-2">Product Price</label>
            <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice"
              placeholder="Enter Product Price">
          </div>

          <div>
            <label class="form-label mt-2">Price Type</label>
            <select class="form-select" id="priceType" name="priceType">
              <option disabled selected value="">Select Type</option>
              <option value="kg">Per Kg</option>
              <option value="unit">Per Item</option>
            </select>
          </div>
          <div class="row">
            <div class="col-7">
              <label for="productImg" class="form-label mt-2">Product Image</label>
              <input type="file" name="productImg" class="form-control" id="productImg" name="productImg"
                placeholder="Enter image of the product">
            </div>
            <div class="col-5">
              <img src="" alt="" id="imgprev">
            </div>
          </div>
          <button type="button" class="btn btn-primary mt-3" id="addprbtn">Add New
            Product</button>
          <button type="button" class="btn btn-primary mt-3" id="editprbtn">Edit
            Product</button>
        </form>
      </div>

      <div class="col-6 px-4 card mt-2 mb-2" style="height: 800px;">
        <h2 class="text-center my-4">All Products</h2>

        <table class="table table-hover">
          <thead>
            <tr class="table-success">
            <td>Product ID</td>
              <td>Product Name</td>
              <td>Category ID</td>
              <td>Category</td>
              <td>Image</td>
              <td>Supplier</td>
              <td>Price</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody id="productlist">

          </tbody>
        </table>



      </div>


    </div>

  </main>



  </body>
  <script src="../../js/jquery.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../../js/sweetalert2.js"></script>
  <script>
    $(document).ready(function () {
      function loadcatdropdown() {
        $.get("../routes/category/loadcatdropdown.php", function (res) {
          $('#productCategory').html("<option disabled selected value=''>Select Product Category</option>" +
            res);
        });
      }


      function loadproductdata() {
        $('#productlist').html("");
        $.get("../routes/product/loadproduct.php", function (res) {
          $('#productlist').html(res);
        });
      }

      // product already existed
      function product_exist() {
        Swal.fire({
          icon: 'error',
          title: 'Productr already exists!',
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



      loadproductdata();
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
                showSuccess(response.message || 'Product Added Successfully!');
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

        var form = $('#addPrForm')[0];
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
              $('#pid').val('');
              loadproductdata();
              $('#imgprev').attr('src', '');
              $('#imgprev').removeAttr('style');
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Failed to Update Product',
                text: data
              });
            }
          },
          error: function () {
            Swal.fire({ icon: 'error', title: 'Request failed' });
          }
        });
      });





    });
  </script>


</html>