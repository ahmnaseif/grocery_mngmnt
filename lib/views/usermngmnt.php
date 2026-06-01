<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}
?>



<!doctype html>
<html lang="en">

<head>
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/fontawesome-free-7.1.0-web/css/all.min.css">
  <title>User Management</title>

  <?php
      include_once('common.php')
      ?>
</head>

<body>
  <main class="app-main">

    <table class="table table-hover">
      <thead>
        <tr class="table-danger">
        <th scope="row">Customer ID</th>
          <th scope="row">User Name</th>
          <th scope="row">Email address</th>
          <th scope="row">Phone Number</th>
          <th scope="row">NIC Number</th>
          <th scope="row">Action</th>
        </tr>
      </thead>
      <tbody id="userlist">
      </tbody>
    </table>




    <div class="modal" id="edituserdata" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div>
              <label for="InputName" class="form-label mt-2">Full Name</label>
              <input type="text" class="form-control" id="InputName" name="customerName" placeholder="Enter your Name">
            </div>

            <div>
              <label for="InputEmail" class="form-label mt-2">Email address</label>
              <input type="email" class="form-control" id="InputEmail" name="customerEmail"
                placeholder="Enter your email address">
              <input type="hidden" id="editid">
            </div>
            <div>
              <label for="NIC" class="form-label mt-2">NIC </label>
              <input type="text" class="form-control" id="NIC" name="customerNIC" placeholder="Enter your NIC">
            </div>
            <div>
              <label for="PhoneNo" class="form-label mt-2">Phone Number</label>
              <input type="text" class="form-control" id="PhoneNo" name="customerPhone"
                placeholder="Enter your phone number">
            </div>

            <div class="row">
              <div class="col-8">
                <label for="dob" class="form-label mt-2">Date of Birth</label>
                <input type="date" class="form-control" name="customerBirthday" id="dob">
              </div>
              <div class="col-4">
                <label for="age" class="form-label mt-2">Age</label>
                <input type="text" class="form-control" readonly id="age">
              </div>
            </div>

            <div>
              <label for="gender" class="form-label mt-2">Gender</label>
              <select class="form-select" name="customerGender" id="gender">
                <option disable selected value=''>Select Gender</option>
                <option value='Male'>Male</option>
                <option value='Female'>Female</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
<script src="../../js/jquery.js"></script>
<!-- <script src="../../js/sweetalert2.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script>
  console.log(typeof Swal);
  // email existed
  function email_exist() {
    Swal.fire({
      icon: 'error',
      title: 'Email already exists!',
      draggable: true,
      backdrop: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $('#InputEmail').attr('class', 'form-control is-invalid');
      }
    });
  }


  // NIC existed
  function nic_exist() {
    Swal.fire({
      icon: 'error',
      title: 'NIC number already exists!',
      draggable: true,
      backdrop: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $('#NIC').attr('class', 'form-control is-invalid');
      }
    });
  }

  // Phone Number existed
  function phnNo_exist() {
    Swal.fire({
      icon: 'error',
      title: 'Phone Number already exists!',
      draggable: true,
      backdrop: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $('#PhoneNo').attr('class', 'form-control is-invalid');
      }
    });
  }


  function edituser(userid) {
    $.get("../routes/customer/loadcusdatabyid.php", {
      userid: userid
    }, function (res) {

      var jdata = JSON.parse(res);

      $('#InputName').val(jdata.customerName);
      $('#InputEmail').val(jdata.customerEmail);
      $('#NIC').val(jdata.customerNIC);
      $('#editid').val(jdata.customerID);
      $('#PhoneNo').val(jdata.customerPhone);
      $('#dob').val(jdata.customerBirthday);
      $('#gender').val(jdata.customerGender);

      const dob = new Date(jdata.customerBirthday);
      const todayday = new Date();
      let age = todayday.getFullYear() - dob.getFullYear();

      $('#age').val(age);

      $('#gender').val(jdata.customerGender);

      // $('#edituserdata').modal('show');
      var myModal = new bootstrap.Modal(document.getElementById('edituserdata'));
      myModal.show();


    })
  }

  $(document).ready(function () {

    function loadalldata() {
      $.get("../routes/customer/loadcusdata.php", function (res) {
        $('#userlist').html(res);
      });
    }
    loadalldata();


    $("#searchtext").on('input change', function () {
      let searchtext = $(this).val();
      if (searchtext != "") {
        $.get("../routes/customer/loadcusdatasearch.php", {
          searchtext: searchtext
        }, function (res) {
          $('#userlist').html(res);
        });
      } else {
        loadalldata();
      }
    });


    //international verify
    $('#PhoneNo').on('input change', function () {
      let value = $(this).val();
      console.log(value);

      value = value.replace(/[^\d+]/g, '');
      if (value.startsWith('0')) {
        value = "+94" + value.slice(1);
      }

      if (value.length > 0 && !value.startsWith('+')) {
        value = "+94" + value;
        value = value.slice(0, 12);

        $(this).val(value);
      }

      if (value.length > 12) {
        value = value.slice(0, 12);
        $(this).val(value);
      }

      if (value.length === 12) {
        $('#PhoneNo').attr('class', 'form-control is-valid');
      } else {
        $('#PhoneNo').attr('class', 'form-control is-invalid');

      }
    });


    //verify email method
    $('#InputEmail').on('input change', function () {
      let value = $(this).val().trim().toLowerCase();
      $(this).val(value);
      const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
      if (emailPattern.test(value)) {
        $('#InputEmail').attr('class', 'form-control is-valid');
      } else {
        $('#InputEmail').attr('class', 'form-control is-invalid');
      }
    });

    //nic check new & old method
    $('#NIC').on('input change', function () {
      let value = $(this).val().trim().toUpperCase();
      const oldNIC = /^\d{9}[XV]$/;
      const newNIC = /^\d{12}$/;
      if (oldNIC.test(value) || newNIC.test(value)) {
        $('#NIC').attr('class', 'form-control is-valid');
      } else {
        $('#NIC').attr('class', 'form-control is-invalid');
      }

      // if (oldNIC.test(value)) {
      //   if (value.length > 10) {
      //     value = value.slice(0, 10);
      //     $(this).val(value);
      //   }


      if (value.length > 12) {
        value = value.slice(0, 12);
        $(this).val(value);
      }
    });



    $('#saveChangesBtn').on('click', function () {
      let name = $('#InputName').val();
      let email = $('#InputEmail').val();
      let nic = $('#NIC').val();
      let phoneno = $('#PhoneNo').val();
      let birthday = $('#dob').val();
      let gender = $('#gender').val();
      let userid = $('#editid').val();
      let error = 0;

      if (name == "") {
        $('#InputName').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#InputName').attr('class', 'form-control is-valid');
      }

      if (email == "") {
        $('#InputEmail').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#InputEmail').attr('class', 'form-control is-valid');
      }

      if (nic == "") {
        $('#NIC').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#NIC').attr('class', 'form-control is-valid');
      }

      if (phoneno == "") {
        $('#PhoneNo').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#PhoneNo').attr('class', 'form-control is-valid');
      }


      if (gender == null) {
        $('#gender').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#gender').attr('class', 'form-control is-valid');
      }
      if (birthday == null) {
        $('#dob').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#dob').attr('class', 'form-control is-valid');
      }


      if (error === 0) {

        $.ajax({
          type: 'POST',
          url: '../routes/customer/editCustomer.php',
          data: {
            customerName: name,
            customerEmail: email,
            customerNIC: nic,
            customerPhone: phoneno,
            customerBirthday: birthday,
            customerGender: gender,
            customerid: userid,
          },
          dataType: 'json',
          success: function (response) {

            if (response.status == true) {
              Swal.fire({
                title: "Account details changed Successfully!",
                icon: "success"
              });

              loadalldata();

              var modal = bootstrap.Modal.getInstance(document.getElementById('edituserdata'));
              modal.hide();

            } else if (response.error_type === "email_exists") {
              email_exist();
            } else if (response.error_type === "nic_exists") {
              nic_exist();
            } else if (response.error_type === "phnNo_exists") {
              phnNo_exist();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Registration Failed!',
                text: response.message || "Something went wrong. Please try again.",
                draggable: true,
                confirmButtonColor: "#dc3545"
              });
            }



          },
          error: function (response) {},
        })
      }

    });


    $(document).on('click', '.deletebtn', function () {

      let customerID = $(this).data('id');
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.get("../routes/customer/deletecusbyid.php", {
            userid: customerID
          }, function (res) {
            if (res == "success") {
              loadalldata();
              Swal.fire({
                title: "Deleted!",
                text: "Account has been deleted.",
                icon: "success"
              });
            } else {
              Swal.fire({
                title: "Something went wrong in deletion!",
                text: "something went wrong",
                icon: "error"
              });
            }
          })
        }
      });
    });

    $(document).on('click', '.deactivatebtn', function () {
      let customerID = $(this).data('id');
      let status = $(this).data('status');

      if (status === "Active") {
        Swal.fire({
          title: "Are you sure?",
          text: "Do you want to deactivate this account?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, deactivate it!"
        }).then((result) => {
          if (result.isConfirmed) {
            $.get("../routes/customer/deactivatebyid.php", {
              userid: customerID
            }, function (res) {
              if (res == "success") {
                loadalldata();
                Swal.fire({
                  title: "Deactivated!",
                  text: "Account has been deactivated.",
                  icon: "success"
                });
              } else {
                Swal.fire({
                  title: "Not deleted!",
                  text: "something went wrong",
                  icon: "error"
                });
              }
            })
          }
        });
      } else {
        Swal.fire({
          title: "Are you sure?",
          text: "Do you want to activate this account?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, activate it!"
        }).then((result) => {
          if (result.isConfirmed) {
            $.get("../routes/customer/deactivatebyid.php", {
              userid: customerID
            }, function (res) {
              if (res == "success") {
                loadalldata();
                Swal.fire({
                  title: "Activated!",
                  text: "Account has been activated.",
                  icon: "success"
                });
              } else {
                Swal.fire({
                  title: "Not deactivated!",
                  text: "something went wrong",
                  icon: "error"
                });
              }
            })
          }
        });
      }

    });





  });
</script>




</html>
