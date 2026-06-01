<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: ../../login.php');
    exit;
}

$page_title = 'Save List';
ob_start();
?>

<div class="alert alert-info d-flex align-items-center gap-2 mb-4">
  <i class="fas fa-info-circle fa-lg"></i>
  Shopping list / wishlist feature is coming soon.
</div>

<a href="/grocery_mngmnt/index.php" class="btn btn-success">
  <i class="fas fa-store me-1"></i> Browse Products
</a>

<?php
$page_content = ob_get_clean();
include_once('commoncstmr.php');
?>
