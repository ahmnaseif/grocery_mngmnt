<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: ../../login.php');
    exit;
}

$page_title = 'Credit History';
ob_start();
?>

<div class="alert alert-info d-flex align-items-center gap-2">
  <i class="fas fa-info-circle fa-lg"></i>
  Credit history and loyalty points feature is coming soon.
</div>

<?php
$page_content = ob_get_clean();
include_once('commoncstmr.php');
?>
