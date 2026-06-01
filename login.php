<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/fontawesome-free-7.1.0-web/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
    .auth-panel {
      background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%);
      min-height: 100vh;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      color: white; text-align: center; padding: 40px 30px;
    }
    .auth-panel img { width: 110px; margin-bottom: 20px; filter: brightness(0) invert(1); }
    .auth-panel h2 { font-weight: 700; margin-bottom: 8px; }
    .auth-panel hr { border-color: rgba(255,255,255,0.3); width: 60%; margin: 20px auto; }
    .auth-panel .feature { margin-bottom: 10px; font-size: 0.95rem; opacity: 0.9; }
    .login-panel {
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      padding: 40px 30px;
    }
    .login-card { width: 100%; max-width: 400px; }
    .login-card h3 { font-weight: 700; color: #1a2a3a; margin-bottom: 6px; }
    .login-card .subtitle { color: #6c757d; margin-bottom: 28px; font-size: 0.9rem; }
    .form-control { border-radius: 10px; padding: 12px 14px; border: 1.5px solid #e0e0e0; }
    .form-control:focus { border-color: #28a745; box-shadow: 0 0 0 3px rgba(40,167,69,0.1); }
    .btn-login {
      background: #28a745; color: white; border: none;
      border-radius: 10px; padding: 12px; font-weight: 600;
      width: 100%; transition: 0.2s;
    }
    .btn-login:hover { background: #1e7e34; }
    .show-pwd { border-radius: 0 10px 10px 0; background: #f8f9fa; border: 1.5px solid #e0e0e0; border-left: none; }
  </style>
</head>
<body>
<?php
include_once('lib/function/AuthFunction.php');
?>
<?php include_once('navbar.php'); ?>

<div class="row g-0" style="min-height: calc(100vh - 56px);">
  <!-- Left panel -->
  <div class="col-lg-6 d-none d-lg-flex auth-panel">
    <div>
      <img src="assets/elements/AMI logo.png" alt="AMI Logo">
      <h2>Welcome Back!</h2>
      <p style="opacity:0.85;">Your Trusted Grocery Partner</p>
      <hr>
      <div class="feature"><i class="fas fa-check-circle me-2"></i> Quality Products from Trusted Suppliers</div>
      <div class="feature"><i class="fas fa-truck me-2"></i> Fast & Reliable Delivery</div>
      <div class="feature"><i class="fas fa-headset me-2"></i> 24/7 Customer Support</div>
    </div>
  </div>

  <!-- Right panel (form) -->
  <div class="col-lg-6 col-12 login-panel bg-white">
    <div class="login-card">
      <h3>Sign In</h3>
      <p class="subtitle">Enter your credentials to continue shopping</p>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input type="email" class="form-control" id="InputEmail1" placeholder="you@example.com">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="InputPassword1" placeholder="Enter your password">
          <button type="button" class="btn show-pwd" id="showpwd1">
            <i id="btn1" class="fa fa-eye-slash"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-end mb-4">
        <a href="passwordreset.php" class="text-success text-decoration-none small">Forgot Password?</a>
      </div>

      <button class="btn-login" id="loginbtn">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
      </button>

      <p class="text-center mt-4 mb-0" style="font-size:0.9rem;">
        Don't have an account?
        <a href="register.php" class="text-success fw-semibold text-decoration-none">Create one</a>
      </p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.js"></script>
<script src="js/sweetalert2.js"></script>
<script>
$(document).ready(function () {
  function accDeact() {
    Swal.fire({ icon: 'error', title: 'Account Deactivated!', text: 'Please contact support.', draggable: true });
  }
  function wrngPasswd() {
    Swal.fire({ icon: 'error', title: 'Incorrect Password!', draggable: true })
      .then(() => $('#InputPassword1').addClass('is-invalid'));
  }
  function wrngEmail() {
    Swal.fire({ icon: 'error', title: 'Email Not Found!', confirmButtonText: 'Try Again', confirmButtonColor: '#dc3545', draggable: true })
      .then(() => $('#InputEmail1').addClass('is-invalid'));
  }
  function fillDet() {
    Swal.fire({ icon: 'warning', title: 'Please fill in all fields!', draggable: true });
  }

  $('#showpwd1').click(function () {
    var f = $('#InputPassword1');
    if (f.attr('type') === 'password') {
      f.attr('type', 'text');
      $('#btn1').attr('class', 'fa fa-eye');
    } else {
      f.attr('type', 'password');
      $('#btn1').attr('class', 'fa fa-eye-slash');
    }
  });

  $('#loginbtn').click(function () {
    $('#InputEmail1, #InputPassword1').removeClass('is-invalid');
    $.ajax({
      type: 'POST',
      url: 'lib/routes/Auth/authentication.php',
      data: {
        loginEmail:  $('#InputEmail1').val(),
        loginPasswd: $('#InputPassword1').val(),
      },
      dataType: 'json',
      success: function (response) {
        if (response.loginstatus === true) {
          Swal.fire({ title: 'Login Successful!', icon: 'success', draggable: true })
            .then(() => window.location.href = response.path);
        } else if (response.error_type === 'acc_deactivated') { accDeact(); }
        else if (response.error_type === 'wrong_password')   { wrngPasswd(); }
        else if (response.error_type === 'email_not_found')  { wrngEmail(); }
        else if (response.error_type === 'fill_in')          { fillDet(); }
        else {
          Swal.fire({ icon: 'error', title: 'Login Failed!', draggable: true });
        }
      }
    });
  });

  // Allow Enter key to submit
  $('#InputEmail1, #InputPassword1').keypress(function (e) {
    if (e.which === 13) $('#loginbtn').click();
  });
});
</script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>
