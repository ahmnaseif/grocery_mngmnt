<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: ../../login.php');
    exit;
}

include_once('../function/main.php');
$main       = new Main();
$conn       = $main->dbResult;
$customerId = $_SESSION['user'];

$stmt = $conn->prepare("SELECT * FROM customer_tbl WHERE customerID = ? AND d_status = 0");
$stmt->bind_param("s", $customerId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header('Location: ../../login.php');
    exit;
}

$page_title = 'My Profile';
ob_start();
?>

<div id="profileAlert" class="alert" style="display:none;"></div>

<div class="card shadow-sm p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Profile Details</h4>
    <button class="btn btn-warning" id="editBtn">
      <i class="fas fa-pen me-1"></i> Edit Profile
    </button>
  </div>

  <form id="profileForm">
    <input type="hidden" name="customerid" value="<?php echo htmlspecialchars($user['customerID']); ?>">

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Customer ID</label>
        <input type="text" class="form-control bg-light"
               value="<?php echo htmlspecialchars($user['customerID']); ?>" readonly disabled>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" class="form-control profile-field" name="customerName"
               value="<?php echo htmlspecialchars($user['customerName']); ?>" readonly>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input type="email" class="form-control profile-field" name="customerEmail"
               value="<?php echo htmlspecialchars($user['customerEmail']); ?>" readonly>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Phone Number</label>
        <input type="text" class="form-control profile-field" name="customerPhone"
               value="<?php echo htmlspecialchars($user['customerPhone']); ?>" readonly>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">NIC Number</label>
        <input type="text" class="form-control profile-field" name="customerNIC"
               value="<?php echo htmlspecialchars($user['customerNIC']); ?>" readonly>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Gender</label>
        <input type="text" class="form-control profile-field" name="customerGender"
               value="<?php echo htmlspecialchars($user['customerGender']); ?>" readonly>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Birthday</label>
        <input type="date" class="form-control profile-field" name="customerBirthday"
               value="<?php echo htmlspecialchars($user['customerBirthday']); ?>" readonly>
      </div>
    </div>

    <div id="saveRow" style="display:none;" class="mt-2">
      <button type="button" class="btn btn-success me-2" id="saveBtn">
        <i class="fas fa-save me-1"></i> Save Changes
      </button>
      <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
  $('#editBtn').click(function () {
    $('.profile-field').prop('readonly', false);
    $('#saveRow').show();
    $(this).hide();
  });

  $('#cancelBtn').click(function () { location.reload(); });

  $('#saveBtn').click(function () {
    $.ajax({
      url: '../routes/customer/editCustomer.php',
      method: 'POST',
      data: $('#profileForm').serialize(),
      success: function (res) {
        if (res === 'success') {
          Swal.fire({ icon: 'success', title: 'Profile updated successfully!' })
            .then(() => location.reload());
        } else {
          Swal.fire({ icon: 'error', title: 'Update failed', text: res });
        }
      }
    });
  });
});
</script>

<?php
$page_content = ob_get_clean();
include_once('commoncstmr.php');
?>
