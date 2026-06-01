<?php 
//start session
session_start();
if(isset($_SESSION['user'])){
    if(isset($_SESSION['usertype'])){
        $usertype = $_SESSION['usertype'];
        if($usertype == "admin"){  // FIXED: Changed = to ==
              
        }else{
            header('Location:../../login1.php');
        }
    }else{
        header('Location:../../login1.php');
    }
}else{
    header('Location:../../login1.php');
}
?>

<!doctype html>
<html lang="en">
<head>
<title>User Management</title>
<?php include_once('common.php') ?>
</head>
<body>
<main class="app-main">
    <table class="table table-hover">
        <thead>
            <tr class="table-danger">
                <th scope="row">User Name</th>
                <th scope="row">Email address</th>
                <th scope="row">Phone Number</th>
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
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="InputName" class="form-label mt-2">Full Name</label>
                        <input type="text" class="form-control" id="InputName" name="customerName" placeholder="Enter your Name">
                    </div>

                    <div>
                        <label for="InputEmail" class="form-label mt-2">Email address</label>
                        <input type="email" class="form-control" id="InputEmail" name="customerEmail" placeholder="Enter your email address">
                        <input type="hidden" id="editid">
                    </div>
                    <div>
                        <label for="NIC" class="form-label mt-2">NIC</label>
                        <input type="text" class="form-control" id="NIC" name="customerNIC" placeholder="Enter your NIC">
                    </div>
                    <div>
                        <label for="PhoneNo" class="form-label mt-2">Phone Number</label>
                        <input type="text" class="form-control" id="PhoneNo" name="customerPhone" placeholder="Enter your phone number">
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
                            <option disabled selected value=''>Select Gender</option>
                            <option value='Male'>Male</option>
                            <option value='Female'>Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button> <!-- FIXED: Added ID -->
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>
<script src="../../js/jquery.js"></script>
<script src="../../js/sweetalert2.js"></script>

<script>
$(document).ready(function () {
    loadalldata();
    
    function loadalldata() {
        $.get("../routes/customer/loademp.php", function (res) {
            $('#userlist').html(res);
        });
    }

    // Search functionality - FIXED
    $("#searchtext").on('input', function () {
        var $searchtext = $(this).val(); // FIXED: Added parentheses
        if ($searchtext != "") {
            $.get("../routes/customer/loadempsearch.php", {
                searchtext: $searchtext
            }, function (res) {
                $('#userlist').html(res);
            });
        } else {
            loadalldata();
        }
    });

    // Age calculation
    $('#dob').on('change', function () {
        const dob = new Date($(this).val());
        const todayday = new Date();
        let age = todayday.getFullYear() - dob.getFullYear();
        $('#age').val(age);
    });

    // FIXED: Changed from on('change') to on('click') for save button
    $('#saveChangesBtn').on('click', function () {
        let name = $('#InputName').val();
        let email = $('#InputEmail').val();
        let nic = $('#NIC').val();
        let phoneno = $('#PhoneNo').val();
        let birthday = $('#dob').val();
        let gender = $('#gender').val();
        let userid = $('#editid').val();
        let error = 0;

        // Validation
        if (name == "") {
            $('#InputName').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#InputName').addClass('is-valid').removeClass('is-invalid');
        }

        if (email == "") {
            $('#InputEmail').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#InputEmail').addClass('is-valid').removeClass('is-invalid');
        }

        if (nic == "") {
            $('#NIC').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#NIC').addClass('is-valid').removeClass('is-invalid');
        }

        if (phoneno == "") {
            $('#PhoneNo').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#PhoneNo').addClass('is-valid').removeClass('is-invalid');
        }

        if (gender == "" || gender == null) {
            $('#gender').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#gender').addClass('is-valid').removeClass('is-invalid');
        }
        
        if (birthday == "" || birthday == null) {
            $('#dob').addClass('is-invalid').removeClass('is-valid');
            error++;
        } else {
            $('#dob').addClass('is-valid').removeClass('is-invalid');
        }

        if (error === 0) {
            $.ajax({
                type: 'POST',
                url: '../routes/customer/editCustomer.php', // FIXED: Corrected path
                data: {
                    customerName: name,
                    customerEmail: email,
                    customerNIC: nic,
                    customerPhone: phoneno,
                    customerBirthday: birthday,
                    customerGender: gender,
                    customerid: userid,
                },
                success: function(response) {
                    if(response == "success") {
                        Swal.fire({
                            title: "Success!",
                            text: "User updated successfully.",
                            icon: "success"
                        });
                        $('#edituserdata').modal('hide');
                        loadalldata(); // Reload the data
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to update user.",
                        icon: "error"
                    });
                },
            });
        } else {
            Swal.fire({
                title: "Validation Error!",
                text: "Please fill all required fields.",
                icon: "warning"
            });
        }
    });

    // Delete user
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
                $.get("../routes/customer/deletempbyid.php", {userid: customerID}, function (res) {
                    if (res == "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: "User has been deleted.",
                            icon: "success"
                        });
                        loadalldata();
                    } else {
                        Swal.fire({
                            title: "Not deleted!",
                            text: "Something went wrong",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });

    // Deactivate/Activate user
    $(document).on('click', '.deactivatebtn', function () {
        let customerID = $(this).data('id');
        let status = $(this).data('status');

        if(status === "Active") {
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
                                title: "Error!",
                                text: "Something went wrong",
                                icon: "error"
                            });
                        }
                    });
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
                                title: "Error!",
                                text: "Something went wrong",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }
    });

    // Edit user function (make this global)
    window.edituser = function($userid) {
        $.get("../routes/customer/loadempbyid.php", {
            userid: $userid
        }, function (res) {
            var jdata = JSON.parse(res); // FIXED: Changed jQuesry.parseJSON to JSON.parse
            
            $('#InputName').val(jdata.customerName);
            $('#InputEmail').val(jdata.customerEmail);
            $('#editid').val(jdata.customerID);
            $('#NIC').val(jdata.customerNIC);
            $('#PhoneNo').val(jdata.customerPhone);
            $('#dob').val(jdata.customerBirthday);
            $('#gender').val(jdata.customerGender);

            const dob = new Date(jdata.customerBirthday);
            const todayday = new Date();
            let age = todayday.getFullYear() - dob.getFullYear();
            $('#age').val(age);
            
            $('#edituserdata').modal('show');
        });
    };
});
</script>

<?php include_once('footer.php') ?>

</body>
</html>