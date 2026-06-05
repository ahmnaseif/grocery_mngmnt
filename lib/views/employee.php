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
<title>Add Staff</title>
</head>
<body>

<?php include_once('common.php'); ?>

<main class="app-main">
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6 card p-4 shadow">
      <h2 class="text-center my-4"><i class="bi bi-person-plus me-2 text-success"></i>Add Staff</h2>

      <div>
        <label for="empName" class="form-label mt-2">Full Name</label>
        <input type="text" class="form-control" id="empName" placeholder="Enter full name">
      </div>

      <div>
        <label for="empEmail" class="form-label mt-2">Email Address</label>
        <input type="email" class="form-control" id="empEmail" placeholder="Enter email address">
      </div>

      <div>
        <label for="empNIC" class="form-label mt-2">NIC</label>
        <input type="text" class="form-control" id="empNIC" placeholder="Enter NIC number">
      </div>

      <div>
        <label for="empPhoneNo" class="form-label mt-2">Phone Number</label>
        <input type="text" class="form-control" id="empPhoneNo" placeholder="Enter phone number">
      </div>

      <div>
        <label for="empgender" class="form-label mt-2">Gender</label>
        <select class="form-select" id="empgender">
          <option disabled selected value=''>Select Gender</option>
          <option value='Male'>Male</option>
          <option value='Female'>Female</option>
        </select>
      </div>

      <!-- ROLE SELECTOR -->
      <div>
        <label for="empRole" class="form-label mt-2">Role</label>
        <select class="form-select" id="empRole">
          <option disabled selected value=''>Select Role</option>
          <option value='employee'>Employee</option>
          <option value='delivery_Person'>Delivery Person</option>
        </select>
      </div>

      <div class="row mt-2">
        <div class="col-11">
          <label for="empPassword" class="form-label mt-2">Password</label>
          <input type="password" class="form-control" id="empPassword" placeholder="Password">
        </div>
        <div class="col-1 mt-4">
          <button type="button" id="showpwd2" class="btn btn-outline-secondary mt-3">
            <i id="btn2" class="fa fa-eye-slash"></i>
          </button>
        </div>
      </div>

      <div class="row">
        <div class="col-11">
          <label for="ConfPasswd" class="form-label mt-2">Confirm Password</label>
          <input type="password" class="form-control" id="ConfPasswd" placeholder="Confirm Password">
        </div>
        <div class="col-1 mt-4">
          <button type="button" id="showconfpwd" class="btn btn-outline-secondary mt-3">
            <i id="btn3" class="fa fa-eye-slash"></i>
          </button>
        </div>
      </div>

      <button type="button" class="btn btn-success mt-4 w-100" id="regempbtn">
        <i class="bi bi-person-check me-2"></i>Create Account
      </button>
    </div>
  </div>
</main>

<script src="../../js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/sweetalert2.js"></script>
<script>
$(document).ready(function () {

  // Phone validation
  $('#empPhoneNo').on('input change', function () {
    let value = $(this).val().replace(/[^\d+]/g, '');
    if (value.startsWith('0')) value = "+94" + value.slice(1);
    if (value.length > 0 && !value.startsWith('+')) { value = "+94" + value; }
    if (value.length > 12) value = value.slice(0, 12);
    $(this).val(value);
    $(this).attr('class', value.length === 12 ? 'form-control is-valid' : 'form-control is-invalid');
  });

  // Email validation
  $('#empEmail').on('input change', function () {
    let value = $(this).val().trim().toLowerCase();
    $(this).val(value);
    const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    $(this).attr('class', emailPattern.test(value) ? 'form-control is-valid' : 'form-control is-invalid');
  });

  // NIC validation
  $('#empNIC').on('input change', function () {
    let value = $(this).val().trim().toUpperCase();
    if (value.length > 12) value = value.slice(0, 12);
    $(this).val(value);
    const oldNIC = /^\d{9}[XV]$/;
    const newNIC = /^\d{12}$/;
    $(this).attr('class', (oldNIC.test(value) || newNIC.test(value)) ? 'form-control is-valid' : 'form-control is-invalid');
  });

  // Confirm password
  $('#ConfPasswd').on('input change', function () {
    const match = $(this).val() === $('#empPassword').val() && $(this).val().length > 0;
    $(this).attr('class', match ? 'form-control is-valid' : 'form-control is-invalid');
  });

  // Show/hide password
  $('#showpwd2').on('click', function () {
    const f = $('#empPassword');
    f.attr('type', f.attr('type') === 'password' ? 'text' : 'password');
    $('#btn2').toggleClass('fa-eye fa-eye-slash');
  });
  $('#showconfpwd').on('click', function () {
    const f = $('#ConfPasswd');
    f.attr('type', f.attr('type') === 'password' ? 'text' : 'password');
    $('#btn3').toggleClass('fa-eye fa-eye-slash');
  });

  // Error alert helpers
  function email_exist()  { Swal.fire({ icon:'error', title:'Email already exists!', draggable:true }).then(() => $('#empEmail').addClass('is-invalid')); }
  function nic_exist()    { Swal.fire({ icon:'error', title:'NIC already exists!', draggable:true }).then(() => $('#empNIC').addClass('is-invalid')); }
  function phnNo_exist()  { Swal.fire({ icon:'error', title:'Phone number already exists!', draggable:true }).then(() => $('#empPhoneNo').addClass('is-invalid')); }

  // Submit
  $('#regempbtn').on('click', function () {
    let employeeName     = $('#empName').val();
    let employeeEmail    = $('#empEmail').val();
    let employeeNIC      = $('#empNIC').val();
    let employeePhone    = $('#empPhoneNo').val();
    let employeeGender   = $('#empgender').val();
    let employeeRole     = $('#empRole').val();
    let employeePassword = $('#empPassword').val();
    let confpasswd       = $('#ConfPasswd').val();
    let error = 0;

    if (!employeeName)     { $('#empName').addClass('is-invalid');    error++; } else { $('#empName').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeeEmail)    { $('#empEmail').addClass('is-invalid');   error++; } else { $('#empEmail').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeeNIC)      { $('#empNIC').addClass('is-invalid');     error++; } else { $('#empNIC').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeePhone)    { $('#empPhoneNo').addClass('is-invalid'); error++; } else { $('#empPhoneNo').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeeGender)   { $('#empgender').addClass('is-invalid');  error++; } else { $('#empgender').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeeRole)     { $('#empRole').addClass('is-invalid');    error++; } else { $('#empRole').removeClass('is-invalid').addClass('is-valid'); }
    if (!employeePassword) { $('#empPassword').addClass('is-invalid'); error++; } else { $('#empPassword').removeClass('is-invalid').addClass('is-valid'); }
    if (!confpasswd || confpasswd !== employeePassword) { $('#ConfPasswd').addClass('is-invalid'); error++; }

    if (error > 0) return;

    $.ajax({
      type: 'POST',
      url: '../routes/employee/addEmployee.php',
      data: {
        employeeName, employeeEmail, employeeNIC,
        employeePhone, employeeGender, employeeRole, employeePassword
      },
      dataType: 'json',
      success: function (response) {
        if (response.status === true) {
          Swal.fire({ title: response.message, icon: 'success', draggable: true })
            .then(() => window.location.href = '../views/employeemngmnt.php');
        } else if (response.error_type === 'email_exists')  { email_exist(); }
          else if (response.error_type === 'nic_exists')    { nic_exist(); }
          else if (response.error_type === 'phnNo_exists')  { phnNo_exist(); }
          else {
            Swal.fire({ icon: 'error', title: 'Failed!', text: response.message || 'Something went wrong.', draggable: true });
          }
      },
      error: function () {
        Swal.fire({ icon: 'error', title: 'Server Error', text: 'Could not connect to server.' });
      }
    });
  });
});
</script>
</body>
</html>
