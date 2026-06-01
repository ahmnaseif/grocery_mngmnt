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
  <title>Employee Management</title>

  <?php
      include_once('common.php')
      ?>
</head>

<body>
  <main class="app-main">

    <table class="table table-hover">
      <thead>
        <tr class="table-danger">
        <th scope="row">Employee ID</th>
          <th scope="row">Name</th>
          <th scope="row">Email address</th>
          <th scope="row">NIC Number</th>
          <th scope="row">Phone Number</th>
          <th scope="row">Action</th>
        </tr>
      </thead>
      <tbody id="emplist">
      </tbody>
    </table>




    <div class="modal" id="editempdata" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="editid">
            <div>
              <label for="empName" class="form-label mt-2">Full Name</label>
              <input type="text" class="form-control" id="empName" name="employeeName"
                placeholder="Enter the employee Name">
            </div>

            <div>
              <label for="empEmail" class="form-label mt-2">Email address</label>
              <input type="email" class="form-control" id="empEmail" name="employeeEmail"
                placeholder="Enter the employee's email address">
            </div>
            <div>
              <label for="empNIC" class="form-label mt-2">NIC </label>
              <input type="text" class="form-control" id="empNIC" name="employeeNIC" placeholder="Enter your NIC">
            </div>
            <div>
              <label for="empPhoneNo" class="form-label mt-2">Phone Number</label>
              <input type="text" class="form-control" id="empPhoneNo" name="empPhoneNo"
                placeholder="Enter the employee's phone number">
            </div>

            <div>
              <label for="empgender" class="form-label mt-2">Gender</label>
              <select class="form-select" name="employeeGender" id="empgender">
                <option disable selected value=''>Select Gender</option>
                <option value='Male'>Male</option>
                <option value='Female'>Female</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveChangesEmBtn">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/jquery.js"></script>
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
        $('#empEmail').attr('class', 'form-control is-invalid');
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
        $('#empNIC').attr('class', 'form-control is-invalid');
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
        $('#empPhoneNo').attr('class', 'form-control is-invalid');
      }
    });
  }


  function edituser(empid) {
    $.get("../routes/employee/loadempbyid.php", {
      empid: empid
    }, function (res) {

      var jdata = JSON.parse(res);

      $('#empName').val(jdata.employeeName);
      $('#empEmail').val(jdata.employeeEmail);
      $('#empNIC').val(jdata.employeeNIC);
      $('#editid').val(jdata.employeeID);
      $('#empPhoneNo').val(jdata.employeePhone);
      $('#empgender').val(jdata.employeeGender);
      // $('#empgender').val(jdata.employeeGender);

      // $('#edituserdata').modal('show');
      var myModal = new bootstrap.Modal(document.getElementById('editempdata'));
      myModal.show();

    })
  }

  $(document).ready(function () {

    function loadalldata() {
      $.get("../routes/employee/loademp.php", function (res) {
        $('#emplist').html(res);
      });
    }
    loadalldata();


    $("#searchtext").on('input', function (res) {

      let searchtext = $(this).val();
      if (searchtext != "") {
        $.get("../routes/employee/loadempsearch.php", {
          searchtext: searchtext
        }, function (res) {
          $('#searchtext').html(res);

        });
      } else {
        loadalldata();
      }
    });


    //international verify
    $('#empPhoneNo').on('input change', function () {
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
        $('#empPhoneNo').attr('class', 'form-control is-valid');
      } else {
        $('#empPhoneNo').attr('class', 'form-control is-invalid');

      }
    });


    //verify email method
    $('#empEmail').on('input change', function () {
      let value = $(this).val().trim().toLowerCase();
      $(this).val(value);
      const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
      if (emailPattern.test(value)) {
        $('#empEmail').attr('class', 'form-control is-valid');
      } else {
        $('#empEmail').attr('class', 'form-control is-invalid');
      }
    });

    //nic check new & old method
    $('#empNIC').on('input change', function () {
      let value = $(this).val().trim().toUpperCase();
      const oldNIC = /^\d{9}[XV]$/;
      const newNIC = /^\d{12}$/;
      if (oldNIC.test(value) || newNIC.test(value)) {
        $('#empNIC').attr('class', 'form-control is-valid');
      } else {
        $('#empNIC').attr('class', 'form-control is-invalid');
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



    $('#saveChangesEmBtn').on('click', function () {
      let name = $('#empName').val();
      let email = $('#empEmail').val();
      let nic = $('#empNIC').val();
      let phoneno = $('#empPhoneNo').val();
      let gender = $('#empgender').val();
      let userid = $('#editid').val();
      let error = 0;

      if (name == "") {
        $('#empName').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empName').attr('class', 'form-control is-valid');
      }

      if (email == "") {
        $('#empEmail').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#empEmail').attr('class', 'form-control is-valid');
      }

      if (nic == "") {
        $('#empNIC').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empNIC').attr('class', 'form-control is-valid');
      }

      if (phoneno == "") {
        $('#empPhoneNo').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#empPhoneNo').attr('class', 'form-control is-valid');
      }


      if (gender == null) {
        $('#empgender').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empgender').attr('class', 'form-control is-valid');
      }


      if (error === 0) {

        $.ajax({
          type: 'POST',
          url: '../routes/employee/editEmployee.php',
          data: {
            employeeName: name,
            employeeEmail: email,
            employeeNIC: nic,
            employeePhone: phoneno,
            employeeGender: gender,
            employeeid: userid,
          },
          dataType: 'json',
          success: function (response) {
            if (response.status === true) {
              Swal.fire({
                title: "Account details cahnged Succcessfully!",
                icon: "success",
                draggable: true
              });
              loadalldata();

              var modal = bootstrap.Modal.getInstance(document.getElementById('editempdata'));
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

      let employeeID = $(this).data('id');
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
          $.get("../routes/employee/deletempbyid.php", {
            userid: customerID
          }, function (res) {
            if (res == "success") {
              loadalldata();
              Swal.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
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
    });

    $(document).on('click', '.deactivatebtn', function () {
      let customerID = $(this).data('id');
      let status = $(this).data('status');

      if (status === "Active") {
        Swal.fire({
          title: "Are your sure?",
          text: "Do you want to deactivate this account?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, deactivate it!"
        }).then((result) => {
          if (result.isConfirmed) {
            $.get("../routes/employee/deactivateEmpbyid.php", {
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
          title: "Are your sure?",
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
                  text: "Account has been activated",
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
