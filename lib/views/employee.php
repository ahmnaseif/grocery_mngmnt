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

<!doctype html>
<html lang="en">

<head>
<link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/fontawesome-free-7.1.0-web/css/all.min.css">
<title>Add Employee</title>
</head>
<body>
  


  <?php
      include_once('common.php')
      ?>

  <main class="app-main">
     <!-- <div class="col-6 px-4 card mt-2 mb-2" style="height: 800px;"> -->
<!-- <div> -->
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="col-md-6 card p-4 shadow">
          <form method="POST">
            <fieldset>
              <h2 class="text-center my-4">Add Employee</h2>


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
                  <input type="text" class="form-control" id="empPhoneNo" name="employeePhone"
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

                <div class="row">
                  <div class="col-11">
                    <label for="empPassword" class="form-label mt-2">Enter password</label>
                    <input type="password" class="form-control" id="empPassword" name="employeePassword"
                      placeholder="Password">
                  </div>
                  <div class="col-1 mt-4">
                    <button type="button" id="showpwd2" class="btn btn-primary mt-3">
                      <i id="btn2" class="fa fa-eye-slash"></i></button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-11">
                    <label for="ConfPasswd" class="form-label mt-2">Confirm your password</label>
                    <input type="password" class="form-control" id="ConfPasswd" name="empConPasswd"
                      placeholder="Confirm Password">
                  </div>
                  <div class="col-1 mt-4">
                    <button type="button" id="showconfpwd" class="btn btn-primary mt-3">
                      <i id="btn3" class="fa fa-eye-slash"></i></button>
                  </div>
                </div>

              

              <button type="button" class="btn btn-primary mt-3" id="regempbtn">Create Account</button>
            </fieldset>
          </form>
      

          </div>
                </div>  





  </main>
</body>


  <script src="../../js/jquery.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../../js/sweetalert2.js"></script>
  <script>
   

    $(document).ready(function () {
    console.log("Here");
    // const today = new Date().toISOString().split('T')[0];
    // $('#dob').attr('max', today);

    // $('#dob').on('change', function () {
    //   // const birth = $(this).val();
    //   // const birthday = new Date(birth);
    //   const dob = new Date($(this).val());
    //   const todayday = new Date();
    //   let age = todayday.getFullYear() - dob.getFullYear();

    //   $('#age').val(age);
    // });



    //lankan verify 
    // $('#PhoneNo').on('input change', function(){
    //   let value= $(this).val();
    //   console.log(value);

    //   value = value.replace(/\D/g, '');
    //   if(value.length>0 && !value.startsWith('0')){
    //     value = "0" + value;
    //   }
    //   if(value.length > 10){
    //     value = value.slice(0,10);
    //   }
    // console.log(value);
    // console.val(value);
    // });

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

    $('#ConfPasswd').on('input change', function () {
      const entd_passwd = $('#empPassword').val();
      const confd_passwd = $(this).val();

      if (confd_passwd === entd_passwd && confd_passwd.length > 0) {

        $('#ConfPasswd').attr('class', 'form-control is-valid');
      } else {
        $('#ConfPasswd').attr('class', 'form-control is-invalid');
      }
    });

    // $('#InputEmail').on('input change', function () {
    //   const entd_email = $('#InputEmail').val();
    //   if (entd_email.includes("@") && !email.includes(" ")) {
    //      $('#InputEmail').attr('class', 'form-control is-valid');
    //   } else {
    //     $('#InputEmail').attr('class', 'form-control is-invalid');
    //   }
    // });


    $('#showpwd2').on('click', function () {
      var passwordField = $('#empPassword');

      if (passwordField.attr('type') == 'password') {
        passwordField.attr('type', 'text');
        $('#btn2').attr('class', 'fa fa-eye');
      } else {
        passwordField.attr('type', 'password');
        $('#btn2').attr('class', 'fa fa-eye-slash');
      }
    });

    $('#showconfpwd').on('click', function () {
      var passwordField = $('#ConfPasswd');

      if (passwordField.attr('type') == 'password') {
        passwordField.attr('type', 'text');
        $('#btn3').attr('class', 'fa fa-eye');
      } else {
        passwordField.attr('type', 'password');
        $('#btn3').attr('class', 'fa fa-eye-slash');
      }
    });

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


    $('#regempbtn').on('click', function () {
      let employeeName = $('#empName').val();
      let employeeEmail = $('#empEmail').val();
      let employeeNIC = $('#empNIC').val();
      let employeePhone = $('#empPhoneNo').val();
      let employeeGender = $('#empgender').val();
      let employeePassword = $('#empPassword').val();
      let confpasswd = $('#ConfPasswd').val();
      let error = 0;
      if (employeeName == "") {
        $('#empName').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empName').attr('class', 'form-control is-valid');
      }

      if (employeeEmail == "") {
        $('#empEmail').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#empEmail').attr('class', 'form-control is-valid');
      }

      if (employeeNIC == "") {
        $('#empNIC').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empNIC').attr('class', 'form-control is-valid');
      }

      if (employeePhone == "") {
        $('#empPhoneNo').attr('class', 'form-control is-invalid');
        error++;
      } else {
        $('#empPhoneNo').attr('class', 'form-control is-valid');
      }


      if (employeeGender == null) {
        $('#empgender').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empgender').attr('class', 'form-control is-valid');
      }
      // if (birthday == null) {
      //   $('#dob').attr('class', 'form-control is-invalid');
      //   error++;

      // } else {
      //   $('#dob').attr('class', 'form-control is-valid');
      // }
      if (employeePassword == "") {
        $('#empPassword').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#empPassword').attr('class', 'form-control is-valid');
      }

      if (confpasswd == "") {
        $('#ConfPasswd').attr('class', 'form-control is-invalid');
        error++;

      } else {
        $('#ConfPasswd').attr('class', 'form-control is-valid');
      }






      if (error === 0) {

        $.ajax({
          type: 'POST',
          url: '../routes/employee/addEmployee.php',
          data: {
            employeeName: employeeName,
            employeeEmail: employeeEmail,
            employeeNIC: employeeNIC,
            employeePhone: employeePhone,
            employeeGender: employeeGender,
            employeePassword: employeePassword,
          },
          dataType: 'json',
          success: function (response) {
            if (response.status === true) {
              Swal.fire({
                title: "Employee Account Registered Succcessfully!",
                icon: "success",
                draggable: true
              }).then(() => {
                window.location.href = '../views/employeemngmnt.php';
              });
            } else if (response.error_type === "email_exists") {
              email_exist();
            } else if (response.error_type === "nic_exists") {
              nic_exist();
            } else if (response.error_type === "phnNo_exists") {
              phnNo_exist();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Registration of employee Failed!',
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




  });
  </script>



</html>