<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/fontawesome-free-7.1.0-web/css/all.min.css">
  <link rel="stylesheet" href="css/sweetalert2.css">
  <title>login</title>
  <style>.image-container img{
  width: 50%;
  height: 30%;
  object-fit: cover; }</style>
</head>

<body>
  <?php
include_once('lib/function/AuthFunction.php');
if(isset($_POST['loginbtn'])){
  $userObj = new Auth;
$result = $userObj->authentication2($_POST["LoginEmail"], $_POST["LoginPassword"]);
}
?>
  <?php 
    include_once('navbar.php');
    ?>
  <div class="container">
    <div class="row">
    <div class="col-lg-7 mt-5"  style="background-color: #78c2ad;">
    <div class style="color: white; text-align: center;">
    <div class="image-container">
  <img src="../grocery_mngmnt/assets/elements/AMI logo.png">
</div>
          
          <p>Your Trusted Grocery Partner</p>
          <hr style="background-color: white; width: 70%; margin: 20px auto;">
          <p><i class="fas fa-check-circle"></i> Quality Products<br>
             <i class="fas fa-truck"></i> Fast Delivery<br>
             <i class="fas fa-headset"></i> 24/7 Customer Support</p>
        </div>
    </div>
<!-- login -->
      <div class="col-lg-5 mt-5"  style="height: 350px">
        <form method="POST">
          <h2 class="text-center my-4">Login</h2>

          <div>
            <label for="InputEmail1" class="form-label mt-2">Email address</label>
            <input type="email" class="form-control" id="InputEmail1" name="loginEmail" placeholder="Enter email">
          </div>

          <div>
            <div class="row">
              <div class="col-11">
                <label for="InputPassword1" class="form-label mt-2">Password</label>
                <input type="password" class="form-control" id="InputPassword1" name="loginPasswd" placeholder="Password">
              </div>

              <div class="col-1 mt-4">
                <button type="button" id="showpwd1" class="btn btn-primary mt-3">
                  <i id="btn1" class="fa fa-eye-slash"></i></button>
              </div>
            </div>

            <div>
              <a href="passwordreset.php">Forgot Password?</a>
            </div>
          </div>

          <button id="loginbtn" type="button" class="btn btn-primary mt-3">SIGN IN</button>

        </form>
        <div>
              <a href="../grocery_mngmnt/login2.php">New Here?</a>
            </div>
      </div>
      



    </div>
      </div>



</body>


<script src="js/jquery.js"></script>
<script src="js/sweetalert2.js"></script>
<script>
  $(document).ready(function () {
    console.log("Here");
   

 // acc deact
 function accDeact() {
      Swal.fire({
        icon: 'error',
        title: 'This account is Deactivated!',
        draggable: true,
        backdrop: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          $('#InputPassword1').attr('class', 'form-control is-invalid');
          $('#InputEmail1').attr('class', 'form-control is-invalid');
        }
      });
    }




 // invalid psswd
 function wrngPasswd() {
      Swal.fire({
        icon: 'error',
        title: 'Incorrect Password!',
        draggable: true,
        backdrop: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          $('#InputPassword1').attr('class', 'form-control is-invalid');
        }
      });
    }

    // invalid email
    function wrngEmail(email1) {
      Swal.fire({
        icon: 'error',
        title: 'Email Not Found!',
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#dc3545',
        showCancelButton: true,
        draggable: true
      }).then((result) => {
        if (result.isConfirmed) {
          $('#InputEmail1').attr('class', 'form-control is-invalid');
        } 
      });
    }

 // fill in
 function fillDet() {
      Swal.fire({
        icon: 'error',
        title: 'Fill input fields!',
        draggable: true
      }).then((result) => {
        if (result.isConfirmed) {
          $('#InputEmail1').attr('class', 'form-control is-invalid');
          $('#InputPassword1').attr('class', 'form-control is-invalid');
        } 
      });
    }




    $('#showpwd1').on('click', function () {
      var passwordField = $('#InputPassword1');

      if (passwordField.attr('type') == 'password') {
        passwordField.attr('type', 'text');
        $('#btn1').attr('class', 'fa fa-eye');
      } else {
        passwordField.attr('type', 'password');
        $('#btn1').attr('class', 'fa fa-eye-slash');
      }
    
});

    $('#loginbtn').on('click', function () {
      let email1 = $('#InputEmail1').val();
      let passwd1 = $('#InputPassword1').val();


      let error = 0;
      //ensure fileds aren't empty
      // if (email1 == "") {
      //   $('#InputEmail1').attr('class', 'form-control is-invalid');
      //   error++;
      // } else {
      //   $('#InputEmail1').attr('class', 'form-control is-valid');
      // }

      // if (passwd1 == "") {
      //   $('#InputPassword1').attr('class', 'form-control is-invalid');
      //   error++;

      // } else {
      //   $('#InputPassword1').attr('class', 'form-control is-valid');
      // }


      if (error === 0) {
      $.ajax({
        type: 'POST',
        url: 'lib/routes/Auth/authentication.php',
        data: {
          loginEmail: email1,
          loginPasswd: passwd1,
        },
        dataType: 'json',
        success: function (response) {
          if (response.loginstatus == true) {
            Swal.fire({
  title: "Login Succcessful!",
  icon: "success",
  draggable: true
            }).then(() => {
              window.location.href = response.path;
            });

          }else if (response.error_type == "acc_deactivated") {
            accDeact();
          }

          else if (response.error_type == "wrong_password") {
            wrngPasswd();
          }

          else if (response.error_type == "email_not_found") {
            wrngEmail(email1);
          }

          else if (response.error_type == "fill_in") {
            fillDet();
          }
          
          else {
             Swal.fire({
              icon: 'error',
              title: 'Login Failed !',
              draggable: true
            });
            $('#InputPassword1').attr('class', 'form-control is-invalid');
            $('#InputEmail1').attr('class', 'form-control is-invalid');
          }
        },
        error: function (response) {},
      })

    }
    });


  
});
</script>


</html>