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
  <style>  .card {
  border-radius: 12px;
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}

.app-main {
  background: #f4f6f9;
  min-height: 100vh;
}</style>
</head>
<title>User Management</title>

<?php
      include_once('common.php')
      ?>
      </head>
<body>
  
    
  <div id="mainContent" class="app-main p-4">

<!-- TOP HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="fw-bold">Dashboard</h3>
  <div>
    <span class="text-muted">Welcome, Admin</span>
  </div>
</div>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">

  <div class="col-md-3">
    <div class="card shadow border-0 p-3 text-center">
      <i class="fas fa-users fa-2x text-primary mb-2"></i>
      <h5>Total Users</h5>
      <h3 id="totalUsers">0</h3>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow border-0 p-3 text-center">
      <i class="fas fa-user-tie fa-2x text-success mb-2"></i>
      <h5>Employees</h5>
      <h3 id="totalEmployees">0</h3>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow border-0 p-3 text-center">
      <i class="fas fa-box fa-2x text-warning mb-2"></i>
      <h5>Products</h5>
      <h3 id="totalProducts">0</h3>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow border-0 p-3 text-center">
      <i class="fas fa-shopping-cart fa-2x text-danger mb-2"></i>
      <h5>Orders</h5>
      <h3 id="totalOrders">0</h3>
    </div>
  </div>

</div>

<!-- QUICK ACTIONS -->
<div class="row g-4 mb-4">

  <div class="col-md-4">
    <div class="card shadow p-3">
      <h5 class="mb-3">Quick Actions</h5>
      <a href="employee.php" class="btn btn-primary w-100 mb-2">+ Add Employee</a>
      <a href="product.php" class="btn btn-success w-100 mb-2">+ Add Product</a>
      <a href="usermngmnt.php" class="btn btn-dark w-100">Manage Users</a>
    </div>
  </div>

  <!-- RECENT ACTIVITY -->
  <div class="col-md-8">
    <div class="card shadow p-3">
      <h5 class="mb-3">Recent Activity</h5>
      <ul class="list-group" id="recentActivity">
        <li class="list-group-item">No recent activity</li>
      </ul>
    </div>
  </div>

</div>

<!-- ANALYTICS / EXTRA SECTION -->
<div class="row g-4">

  <div class="col-md-6">
    <div class="card shadow p-3">
      <h5>System Status</h5>
      <p class="text-success">✔ Server Running</p>
      <p class="text-primary">✔ Database Connected</p>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow p-3">
      <h5>Admin Info</h5>
      <p>Name: Admin</p>
      <p>Email: admin@gmail.com</p>
    </div>
  </div>

</div>

</div>
  

</body>
 
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/jquery.js"></script>
<script src="../../js/sweetalert2.js"></script>
  <script>
  $(document).ready(function () {
    $.getJSON('../routes/stats/getDashboardStats.php', function (data) {
      $('#totalUsers').text(data.customers);
      $('#totalEmployees').text(data.employees);
      $('#totalProducts').text(data.products);
      $('#totalOrders').text(data.orders);
    });
  });
  </script>
  <script>
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


function edituser($userid) {
        $.get("../routes/customer/loadcusdatabyid.php", {
        userid: $userid
      }, function (res) {

        // var jdata = jQuesry.parseJSON(res);
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
        $('#edituserdata').modal('show');

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
     

      $("#searchtext").on('input', function () {
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


      $('#dob').on('change', function () {
        const dob = new Date($(this).val());
        const todayday = new Date();
        let age = todayday.getFullYear() - dob.getFullYear();

        $('#age').val(age);
      });
//international verify
$('#PhoneNo').on('change', function () {
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
        $('#InputEmail').attr('class', 'form-control is-invalid');}
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




    // $('#edituserdata').on('click', function () {
    //   var myModal = new bootstrap.Modal(document.getElementById('edituserdata'));
    //       myModal.show();
    // });











       $('#edituserdata').on('click', function () {
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
          if (response.status === true) {
            Swal.fire({
  title: "Account details changed successfully!",
  icon: "success",
  draggable: true
            });
          }
        
          else if (response.error_type === "email_exists") {
                    email_exist();
                }
                else if (response.error_type === "nic_exists") {
                  nic_exist();
                }

                else if (response.error_type === "phnNo_exists") {
                  phnNo_exist();
                }
                
                else {
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
            $.get("../routes/customer/deletecusbyid.php", {userid: customerID}, function (res) {
              if (res == "success") {
                loadalldata();
                Swal.fire({
                  title: "Deleted!",
                  text: "Your file has been deleted.",
                  icon: "success"
                });
              } else {
                Swal.fire({
                  title: "NOt deleted!",
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

                if(status === "Active"){
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
                    $.get("../routes/customer/deactivatebyid.php", {userid: customerID}, function (res) {
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
                }else{ Swal.fire({
                    title: "Are you sure?",
                  text: "Do you want to activate this account?",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, activate it!"
                  }).then((result) => {
                  if (result.isConfirmed) {
                    $.get("../routes/customer/deactivatebyid.php", {userid: customerID}, function (res) {
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
