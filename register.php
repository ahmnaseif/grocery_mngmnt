<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
    .auth-panel {
      background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%);
      color: white; text-align: center; padding: 60px 40px;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .auth-panel img { width: 100px; margin-bottom: 20px; filter: brightness(0) invert(1); }
    .auth-panel h2 { font-weight: 700; margin-bottom: 8px; }
    .auth-panel hr { border-color: rgba(255,255,255,0.3); width: 60%; margin: 20px auto; }
    .auth-panel .feature { margin-bottom: 10px; font-size: 0.95rem; opacity: 0.9; }
    .reg-panel { background: white; padding: 40px 36px; }
    .reg-panel h3 { font-weight: 700; color: #1a2a3a; margin-bottom: 4px; }
    .reg-panel .subtitle { color: #6c757d; margin-bottom: 24px; font-size: 0.9rem; }
    .form-control, .form-select { border-radius: 10px; padding: 11px 14px; border: 1.5px solid #e0e0e0; font-size: 0.9rem; }
    .form-control:focus, .form-select:focus { border-color: #28a745; box-shadow: 0 0 0 3px rgba(40,167,69,0.1); }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #444; margin-bottom: 5px; }
    .btn-register {
      background: #28a745; color: white; border: none; border-radius: 10px;
      padding: 12px; font-weight: 600; width: 100%; font-size: 1rem; transition: 0.2s;
    }
    .btn-register:hover { background: #1e7e34; }
    .show-pwd-btn { border-radius: 0 10px 10px 0; background: #f8f9fa; border: 1.5px solid #e0e0e0; border-left: none; }
  </style>
</head>
<body>
<?php include_once('lib/function/AuthFunction.php'); include_once('lib/function/customerFunction.php'); ?>
<?php include_once('navbar.php'); ?>

<div class="row g-0">
  <!-- Left panel -->
  <div class="col-lg-5 d-none d-lg-flex auth-panel">
    <div>
      <img src="assets/elements/AMI logo.png" alt="AMI Logo">
      <h2>Join AMI Grocery</h2>
      <p style="opacity:0.85;">Your Trusted Grocery Partner</p>
      <hr>
      <div class="feature"><i class="fas fa-check-circle me-2"></i> Quality Products from Trusted Suppliers</div>
      <div class="feature"><i class="fas fa-truck me-2"></i> Fast & Reliable Delivery</div>
      <div class="feature"><i class="fas fa-headset me-2"></i> 24/7 Customer Support</div>
    </div>
  </div>

  <!-- Right panel -->
  <div class="col-lg-7 col-12 reg-panel">
    <h3>Create Account</h3>
    <p class="subtitle">Fill in your details to get started</p>

    <form id="regForm">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" id="InputName" placeholder="Enter your name">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" id="InputEmail" placeholder="you@gmail.com">
        </div>
        <div class="col-md-6">
          <label class="form-label">NIC Number</label>
          <input type="text" class="form-control" id="NIC" placeholder="123456789V or 200012345678">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="PhoneNo" placeholder="+94XXXXXXXXX">
        </div>
        <div class="col-md-5">
          <label class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob">
        </div>
        <div class="col-md-3">
          <label class="form-label">Age</label>
          <input type="text" class="form-control" readonly id="age" placeholder="—">
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select class="form-select" id="gender">
            <option disabled selected value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="InputPassword" placeholder="Create a password">
            <button type="button" class="btn show-pwd-btn" id="showpwd2"><i id="btn2" class="fa fa-eye-slash"></i></button>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Confirm Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="ConfPasswd" placeholder="Repeat your password">
            <button type="button" class="btn show-pwd-btn" id="showconfpwd"><i id="btn3" class="fa fa-eye-slash"></i></button>
          </div>
        </div>
      </div>

      <button type="button" class="btn-register mt-4" id="regbtn">
        <i class="fas fa-user-plus me-2"></i>Create Account
      </button>
    </form>

    <p class="text-center mt-3 mb-0" style="font-size:0.9rem;">
      Already have an account?
      <a href="login.php" class="text-success fw-semibold text-decoration-none">Sign in</a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.js"></script>
<script src="js/sweetalert2.js"></script>
<script>
$(document).ready(function () {
  const today = new Date().toISOString().split('T')[0];
  $('#dob').attr('max', today);

  $('#dob').on('change', function () {
    const dob = new Date($(this).val());
    const todayday = new Date();
    let age = todayday.getFullYear() - dob.getFullYear();
    $('#age').val(age);
  });

  $('#PhoneNo').on('input change', function () {
    let value = $(this).val().replace(/[^\d+]/g, '');
    if (value.startsWith('0')) value = "+94" + value.slice(1);
    if (value.length > 0 && !value.startsWith('+')) value = "+94" + value;
    if (value.length > 12) value = value.slice(0, 12);
    $(this).val(value);
    $(this).toggleClass('is-valid', value.length === 12).toggleClass('is-invalid', value.length > 0 && value.length !== 12);
  });

  $('#InputEmail').on('input change', function () {
    let value = $(this).val().trim().toLowerCase();
    $(this).val(value);
    const ok = /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(value);
    $(this).toggleClass('is-valid', ok).toggleClass('is-invalid', !ok && value.length > 0);
  });

  $('#NIC').on('input change', function () {
    let value = $(this).val().trim().toUpperCase();
    if (value.length > 12) value = value.slice(0, 12);
    $(this).val(value);
    const ok = /^\d{9}[XV]$/.test(value) || /^\d{12}$/.test(value);
    $(this).toggleClass('is-valid', ok).toggleClass('is-invalid', !ok && value.length > 0);
  });

  $('#ConfPasswd').on('input change', function () {
    const match = $(this).val() === $('#InputPassword').val() && $(this).val().length > 0;
    $(this).toggleClass('is-valid', match).toggleClass('is-invalid', !match && $(this).val().length > 0);
  });

  function togglePwd(fieldId, iconId) {
    const f = $(fieldId);
    const isPass = f.attr('type') === 'password';
    f.attr('type', isPass ? 'text' : 'password');
    $(iconId).attr('class', isPass ? 'fa fa-eye' : 'fa fa-eye-slash');
  }
  $('#showpwd2').click(function () { togglePwd('#InputPassword', '#btn2'); });
  $('#showconfpwd').click(function () { togglePwd('#ConfPasswd', '#btn3'); });

  function email_exist() {
    Swal.fire({ icon: 'error', title: 'Email already exists!', draggable: true })
      .then(() => $('#InputEmail').addClass('is-invalid'));
  }
  function nic_exist() {
    Swal.fire({ icon: 'error', title: 'NIC already exists!', draggable: true })
      .then(() => $('#NIC').addClass('is-invalid'));
  }
  function phnNo_exist() {
    Swal.fire({ icon: 'error', title: 'Phone number already exists!', draggable: true })
      .then(() => $('#PhoneNo').addClass('is-invalid'));
  }

  $('#regbtn').click(function () {
    const name     = $('#InputName').val().trim();
    const email    = $('#InputEmail').val().trim();
    const nic      = $('#NIC').val().trim();
    const phoneno  = $('#PhoneNo').val().trim();
    const birthday = $('#dob').val();
    const age      = $('#age').val();
    const gender   = $('#gender').val();
    const passwd   = $('#InputPassword').val();
    const confpasswd = $('#ConfPasswd').val();

    let error = 0;
    if (!name)     { $('#InputName').addClass('is-invalid');     error++; }
    if (!email)    { $('#InputEmail').addClass('is-invalid');    error++; }
    if (!nic)      { $('#NIC').addClass('is-invalid');           error++; }
    if (!phoneno)  { $('#PhoneNo').addClass('is-invalid');       error++; }
    if (!gender)   { $('#gender').addClass('is-invalid');        error++; }
    if (!birthday) { $('#dob').addClass('is-invalid');           error++; }
    if (!passwd)   { $('#InputPassword').addClass('is-invalid'); error++; }
    if (confpasswd !== passwd) { $('#ConfPasswd').addClass('is-invalid'); error++; }

    if (error > 0) return;

    $.ajax({
      type: 'POST',
      url: 'lib/routes/customer/addCustomer.php',
      data: { customerName: name, customerEmail: email, customerNIC: nic, customerPhone: phoneno,
              customerGender: gender, customerBirthday: birthday, customerAge: age, customerPasswd: passwd },
      dataType: 'json',
      success: function (response) {
        if (response.status === true) {
          Swal.fire({ title: 'Account Created!', icon: 'success', draggable: true })
            .then(() => window.location.href = 'lib/views/dshbdcustomer.php');
        } else if (response.error_type === 'email_exists')  { email_exist(); }
        else if (response.error_type === 'nic_exists')      { nic_exist(); }
        else if (response.error_type === 'phnNo_exists')    { phnNo_exist(); }
        else {
          Swal.fire({ icon: 'error', title: 'Registration Failed!',
            text: response.message || 'Something went wrong.', confirmButtonColor: '#dc3545' });
        }
      }
    });
  });
});
</script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>
