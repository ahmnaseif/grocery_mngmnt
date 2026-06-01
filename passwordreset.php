<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/fontawesome-free-7.1.0-web/css/all.min.css">
  <link rel="stylesheet" href="css/sweetalert2.css">
  <title>Reset Password</title>
</head>
<body>
<?php include_once('navbar.php'); ?>

<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-5">
      <h2 class="text-center mb-4">Reset Password</h2>

      <!-- Step 1: Email -->
      <div id="step1">
        <p class="text-muted text-center">Enter your registered email address.</p>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" id="resetEmail" placeholder="Enter your email">
        </div>
        <button id="verifyEmailBtn" class="btn btn-primary w-100">Continue</button>
        <div class="mt-3 text-center">
          <a href="login.php">Back to Login</a>
        </div>
      </div>

      <!-- Step 2: NIC + New Password (hidden initially) -->
      <div id="step2" style="display: none;">
        <p class="text-muted text-center">Enter your NIC to verify your identity and set a new password.</p>
        <div class="mb-3">
          <label class="form-label">NIC Number</label>
          <input type="text" class="form-control" id="resetNIC" placeholder="Enter your NIC">
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
            <button type="button" class="btn btn-outline-secondary" id="toggleNewPwd">
              <i id="eyeIcon" class="fa fa-eye-slash"></i>
            </button>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
        </div>
        <button id="resetBtn" class="btn btn-success w-100">Reset Password</button>
        <div class="mt-3 text-center">
          <a href="#" id="backToStep1">Use a different email</a>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/sweetalert2.js"></script>
<script>
  $(document).ready(function () {

    // Toggle password visibility
    $('#toggleNewPwd').on('click', function () {
      var field = $('#newPassword');
      if (field.attr('type') === 'password') {
        field.attr('type', 'text');
        $('#eyeIcon').attr('class', 'fa fa-eye');
      } else {
        field.attr('type', 'password');
        $('#eyeIcon').attr('class', 'fa fa-eye-slash');
      }
    });

    // Step 1: Verify email
    $('#verifyEmailBtn').on('click', function () {
      var email = $('#resetEmail').val().trim();
      if (email === '') {
        $('#resetEmail').addClass('is-invalid');
        return;
      }
      $('#resetEmail').removeClass('is-invalid');

      $.ajax({
        type: 'POST',
        url: 'lib/routes/Auth/resetPassword.php',
        data: { action: 'verify', email: email },
        dataType: 'json',
        success: function (res) {
          if (res.status) {
            $('#step1').hide();
            $('#step2').show();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Email Not Found',
              text: res.message,
              draggable: true
            });
            $('#resetEmail').addClass('is-invalid');
          }
        },
        error: function () {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
        }
      });
    });

    // Allow pressing Enter on email field
    $('#resetEmail').on('keypress', function (e) {
      if (e.which === 13) $('#verifyEmailBtn').click();
    });

    // Back to step 1
    $('#backToStep1').on('click', function (e) {
      e.preventDefault();
      $('#step2').hide();
      $('#step1').show();
    });

    // Step 2: Reset password
    $('#resetBtn').on('click', function () {
      var email       = $('#resetEmail').val().trim();
      var nic         = $('#resetNIC').val().trim();
      var newPassword = $('#newPassword').val();
      var confirmPwd  = $('#confirmPassword').val();
      var hasError    = false;

      if (nic === '') {
        $('#resetNIC').addClass('is-invalid');
        hasError = true;
      } else {
        $('#resetNIC').removeClass('is-invalid');
      }

      if (newPassword.length < 4) {
        $('#newPassword').addClass('is-invalid');
        hasError = true;
      } else {
        $('#newPassword').removeClass('is-invalid');
      }

      if (newPassword !== confirmPwd) {
        $('#confirmPassword').addClass('is-invalid');
        Swal.fire({ icon: 'error', title: 'Passwords do not match', draggable: true });
        hasError = true;
      } else {
        $('#confirmPassword').removeClass('is-invalid');
      }

      if (hasError) return;

      $.ajax({
        type: 'POST',
        url: 'lib/routes/Auth/resetPassword.php',
        data: { action: 'reset', email: email, nic: nic, newPassword: newPassword },
        dataType: 'json',
        success: function (res) {
          if (res.status) {
            Swal.fire({
              icon: 'success',
              title: 'Password Reset Successful!',
              text: 'You can now log in with your new password.',
              draggable: true
            }).then(function () {
              window.location.href = 'login.php';
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Reset Failed',
              text: res.message,
              draggable: true
            });
            if (res.error_type === 'no_match') {
              $('#resetNIC').addClass('is-invalid');
            }
          }
        },
        error: function () {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
        }
      });
    });

  });
</script>
</body>
</html>
